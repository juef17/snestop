<?php namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table = 'User';
	
	public function login($username, $password)
	{
		$this->pruneUnconfirmedUsers(); //ca me prenait un trigger quelconque, 1 fois par jour...
		
		$builder = $this->db->table('User');
		$builder->select('*');
		$builder->limit(1);
		$builder->where('userName', $username);
		
		if($builder->countAllResults(false) == 1){
			$query = $builder->get();
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			$user = $this->getUserFromRow($row);
		}
		else
			return false;
		
		if($this->validate_password($password, $user->passwordSalt, $user->password))
			return $user;
		else
			return false;
	}

	public function remembered_login($token) {
		$builder = $this->db->table('User');
		$builder->where('rememberMeSnestopToken', $token);
		$builder->limit(1);

		if($builder->countAllResults(false) == 1){
			$query = $builder->get();
			return $this->getUserFromRow($query->getResult());
		}
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
		
		$builder = $this->db->table('User');
		$builder->where('userName', $username);
		return $builder->update($data);
	}

	public function get_user($id = FALSE) {
		$builder = $this->db->table('User');
		$builder->select('*');
		if($id === FALSE) {
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$builder->limit(1);
			$builder->where('idUser', $id);
			$query = $builder->get();
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		}
	}

	public function get_user_profile($username) {
		$builder = $this->db->table('User');
		$builder->select('*, Community.name as communityName, Community.URL as communityURL, registrationDate');
		$builder->where('userName', $username);
		$builder->where('registrationToken', NULL);
		$builder->join('Community', 'User.idCommunity = Community.idCommunity', 'left');
		$builder->limit(1);
		$query = $builder->get();
		foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
		return $row;
	}

	public function get_users_list() {
		$builder = $this->db->table('User');
		$builder->select('*, Community.name as communityName');
		$builder->join('Community', 'User.idCommunity = Community.idCommunity', 'left');
		$query = $builder->get();

		$retval = array();
		foreach($query->getResult() as $row) {
			$retval[] = $this->getUserFromRow($row);
		}
		return $retval;
	}

	public function setUserEnabledState($id, $enabled) {
		$builder = $this->db->table('User');
		$builder->where('idUser', $id);
		$data = array(
			'enabled' => ($enabled ? 1 : 0)
		);
		if(!$enabled)
			$data += ['rememberMeSnestopToken' => ''];
		return $builder->update($data);
	}

	public function setPlayerModes($id, $loop, $randomize) {
		$builder = $this->db->table('User');
		$builder->where('idUser', $id);
		$data = array(
			'loop' => $loop,
			'randomize' => $randomize
		);
		return $builder->update($data);
	}

	public function deleteUser($id) {
		$builder = $this->db->table('User');
		$builder->where('idUser', $id);
		$builder->delete();
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
		$builder = $this->db->table('User');
		$builder->set('registrationDate', 'NOW()', FALSE);
		return $builder->insert($data);
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

		$builder = $this->db->table('User');
		$builder->where('idUser', $idUser);
		return $builder->update($data);
		//TODO: si user change de community, faut faire... d'zaffaires!!! ajouter un trigger dans BD si trop lourd.
	}

	public function resetPassword($idUser) {
		$data = array();
		$passwordEncoding = $this->create_hash('iwillnotforgetagain');
		$data['password'] = $passwordEncoding['hash'];
		$data['passwordSalt'] = $passwordEncoding['salt'];

		$builder = $this->db->table('User');
		$builder->where('idUser', $idUser);
		return $builder->update($data);
	}

	public function removeRegistrationToken($token) {
		$builder = $this->db->table('User');
		$builder->select('idUser');
		$builder->where('registrationToken', $token);
		$builder->limit(1);
		if($builder->countAllResults(false) == 1)
		{
			$query = $builder->get();
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			$id = $row()->idUser;
			$builderUpdate = $this->db->table('User');
			$builderUpdate->where('idUser', $idUser);
			$data = array('registrationToken' => NULL);
			return $builderUpdate->update($data);
		}
		else
			return false;
	}

	public function pruneUnconfirmedUsers() {
		$builder = $this->db->table('User');		
		$builder->where('registrationToken IS NOT NULL', NULL, FALSE);
		$builder->where('registrationDate < DATE_SUB(NOW(),INTERVAL 1 DAY)', NULL, FALSE);
		$builder->where('isAdmin', 0);
		$last_query = $builder->getCompiledSelect(false);
		$builder->delete();
		return $last_query;
	}

	public function search($searchString) {
		$regex = implode('.+', explode(' ', preg_quote($searchString)));
		$regex = $this->db->escapeLikeString($regex);
		
		//TODO: Les recherches pour * cherchent littéralement le caractère *... pis c'est comme ça aussi pour les jeux et les tracks
		
		$builder = $this->db->table('User');	
		$builder->select('*, Community.name as communityName, Community.URL as communityUrl, count(DuelResult.idUser) as duelzTaken');
		$builder->join('Community', 'User.idCommunity = Community.idCommunity', 'left');
		$builder->join('DuelResult', 'User.idUser = DuelResult.idUser', 'left');
		$builder->where("(userName RLIKE '{$regex}')");
		$builder->groupBy('DuelResult.idUser');
		$builder->limit(50);
		$builder->orderBy('userName');
		
		$query = $builder->get();
		$retval = array();
		foreach($query->getResult() as $row)
			$retval[] = $this->getUserFromRow($row);

		return $retval;
	}
	
	private function create_hash($password) {
		$salt = base64_encode(random_bytes(24));
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