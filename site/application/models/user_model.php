<?php
class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function login($username, $password) {
		$this->pruneUnconfirmedUsers(); //ca me prenait un trigger quelconque, 1 fois par jour...

		$this->db->where('userName', $username);
		$this->db->limit(1);
		$query = $this->db->get('User');
		if($query->num_rows() == 1)
			$user = $this->getUserFromRow($query->row());
		else
			return false;
		
		if($this->validate_password($password, $user->passwordSalt, $user->password))
			return $user;
		else
			return false;
	}

	public function remembered_login($token) {
		$this->db->where('rememberMeSnestopToken', $token);
		$this->db->limit(1);

		$query = $this->db->get('User');
		if($query->num_rows() == 1)
			return $this->getUserFromRow($query->row());
		else
			return false;
	}

	private function getUserFromRow($row) {
		$row->isAdmin = ord($row->isAdmin) == 1 || $row->isAdmin == 1;
		$row->enabled = ord($row->enabled) == 1 || $row->enabled == 1;
		$row->canStreamMP3 = ord($row->canStreamMP3) == 1 || $row->canStreamMP3 == 1;
		$row->randomize = ord($row->randomize) == 1 || $row->randomize == 1;
		return $row;
	}

	public function set_token($username, $token) {
		$data = array(
			'rememberMeSnestopToken' => $token
		);
		$this->db->where('userName', $username);
		return $this->db->update('User', $data);
	}

	public function get_user($id = FALSE) {
		if($id === FALSE) {
			$query = $this->db->get('User');
			return $query->result_array();
		} else {
			$query = $this->db->get_where('User', array('idUser' => $id));
			return $query->row();
		}
	}

	public function get_user_profile($username) {
		$this->db->select('*, Community.name as communityName, Community.URL as communityURL, registrationDate');
		$this->db->where('userName', $username);
		$this->db->where('registrationToken', NULL);
		$this->db->join('Community', 'User.idCommunity = Community.idCommunity', 'left');
		$this->db->limit(1);
		$query = $this->db->get('User');
		return $query->row();
	}

	public function get_users_list() {
		$this->db->select('*, Community.name as communityName');
		$this->db->from('User');
		$this->db->join('Community', 'User.idCommunity = Community.idCommunity', 'left');
		$query = $this->db->get();

		$retval = array();
		foreach($query->result() as $row) {
			$retval[] = $this->getUserFromRow($row);
		}
		return $retval;
	}

	public function setUserEnabledState($id, $enabled) {
		if($enabled) {
			$this->db->update('User', array('enabled' => 1), "idUser = $id");
		} else {
			$this->db->update('User', array('enabled' => 0, 'rememberMeSnestopToken' => ''), "idUser = $id");
		}
	}

	public function setPlayerModes($id, $loop, $randomize) {
		return $this->db->update('User', array('loop' => $loop, 'randomize' => $randomize), "idUser = $id");
	}

	public function deleteUser($id) {
		return $this->db->delete('User', array('idUser' => $id));
	}

	public function addUser($username, $password, $email, $language, $registerToken, $idCommunity) {
		$passwordEncoding = $this->create_hash($password);
		$data = array(
			'userName' => $username,
			'password' => $passwordEncoding['hash'],
			'passwordSalt' => $passwordEncoding['salt'],
			'email' => $email,
			'language' => $language,
			'registrationToken' => $registerToken,
			'idCommunity' => $idCommunity
		);
		$this->db->set('registrationDate', 'NOW()', FALSE);
		return $this->db->insert('User', $data);
	}

	public function updateUser($idUser, $username, $password, $email, $language, $idCommunity) {
		$data = array(
			'userName' => $username,
			'email' => $email,
			'language' => $language,
			'idCommunity' => $idCommunity
		);
		if($password != '') {
			$passwordEncoding = $this->create_hash($password);
			$data['password'] = $passwordEncoding['hash'];
			$data['passwordSalt'] = $passwordEncoding['salt'];
		}

		$this->db->where('idUser', $idUser);
		return $this->db->update('User', $data);
		//TODO: si user change de community, faut faire... d'zaffaires!!! ajouter un trigger dans BD si trop lourd.
	}

	public function resetPassword($idUser) {
		$data = array();
		$passwordEncoding = $this->create_hash('iwillnotforgetagain');
		$data['password'] = $passwordEncoding['hash'];
		$data['passwordSalt'] = $passwordEncoding['salt'];

		$this->db->where('idUser', $idUser);
		return $this->db->update('User', $data);
	}

	public function removeRegistrationToken($token) {
		$this->db->select('idUser');
		$this->db->from('User');
		$this->db->where('registrationToken', $token);
		$this->db->limit(1);
		$query = $this->db->get();
		
		if($query->num_rows() == 1) {
			$id = $query->row()->idUser;
			return $this->db->update('User', array('registrationToken' => NULL), "idUser = $id");
		} else {
			return FALSE;
		}
	}

	public function pruneUnconfirmedUsers() {
		$this->db->where('registrationToken IS NOT NULL', NULL, FALSE);
		$this->db->where('registrationDate < DATE_SUB(NOW(),INTERVAL 1 DAY)', NULL, FALSE);
		$this->db->where('isAdmin', 0);
		$this->db->delete('User');
		return $this->db->last_query();
	}

	public function search($searchString) {
		$regex = implode('.+', explode(' ', preg_quote($searchString)));
		$regex = $this->db->escape_str($regex);
		
		$this->db->select('*, Community.name as communityName, Community.URL as communityUrl');
		$this->db->from('User');
		$this->db->join('Community', 'User.idCommunity = Community.idCommunity', 'left');
		$this->db->where('userName RLIKE', "{$regex}");
		$this->db->limit(50);
		
		$query = $this->db->get();
		$retval = array();
		foreach($query->result() as $row)
			$retval[] = $this->getUserFromRow($row);

		return $retval;
	}
	
	private function create_hash($password) {
		$salt = base64_encode(mcrypt_create_iv(24, MCRYPT_DEV_URANDOM));
		$hash = base64_encode($this->pbkdf2(
				"sha256",
				$password,
				$salt,
				1000,
				24,
				true
			));
		return array("salt" => $salt, "hash" => $hash);
	}
	
	private function validate_password($password, $salt, $correct_hash)
	{
		$correct_hash_decoded = base64_decode($correct_hash);
		$current_password_hashed = $this->pbkdf2(
				"sha256",
				$password,
				$salt,
				1000,
				24,
				true
			);
		return $this->slow_equals($correct_hash_decoded, $current_password_hashed);
	}
	
	private function slow_equals($a, $b)
	{
		$diff = strlen($a) ^ strlen($b);
		for($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
		{
			$diff |= ord($a[$i]) ^ ord($b[$i]);
		}
		return $diff === 0; 
	}
	
	private function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false)
	{
		$algorithm = strtolower($algorithm);
		if(!in_array($algorithm, hash_algos(), true))
			trigger_error('PBKDF2 ERROR: Invalid hash algorithm.', E_USER_ERROR);
		if($count <= 0 || $key_length <= 0)
			trigger_error('PBKDF2 ERROR: Invalid parameters.', E_USER_ERROR);
		if (function_exists("hash_pbkdf2")) {
			if (!$raw_output) {
				$key_length = $key_length * 2;
			}
			return hash_pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output);
		}
		$hash_length = strlen(hash($algorithm, "", true));
		$block_count = ceil($key_length / $hash_length);
		$output = "";
		for($i = 1; $i <= $block_count; $i++) {
			$last = $salt . pack("N", $i);
			$last = $xorsum = hash_hmac($algorithm, $last, $password, true);
			for ($j = 1; $j < $count; $j++) {
				$xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
			}
			$output .= $xorsum;
		}
		if($raw_output)
			return substr($output, 0, $key_length);
		else
			return bin2hex(substr($output, 0, $key_length));
	}
}
