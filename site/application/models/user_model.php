<?php
class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function login($username, $password) {
		$this->pruneUnconfirmedUsers(); //ca me prenait un trigger quelconque, 1 fois par jour...
		
		$this->db->where('userName', $username);
		$this->db->where('password', MD5($password));
		$this->db->limit(1);

		$query = $this->db->get('User');
		if($query->num_rows() == 1)
			return $this->getUserFromRow($query->row());
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
		$this->db->select('userName, language, Community.name as communityName, Community.URL as communityURL, registrationDate');
		$this->db->where('userName', $username);
		$this->db->where('registrationToken', NULL);
		$this->db->join('Community', 'User.idCommunity = Community.idCommunity', 'left');
		$this->db->limit(1);
		$query = $this->db->get('User');
		return $query->row();
	}

	public function get_users_list() {
		$this->db->select('idUser, userName, email, language, canStreamMP3, registrationToken, isAdmin, enabled, registrationDate, Community.name as communityName');
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

	public function deleteUser($id) {
		return $this->db->delete('User', array('idUser' => $id));
	}

	public function addUser($username, $password, $email, $language, $registerToken, $idCommunity) {
		$data = array(
			'userName' => $username,
			'password' => MD5($password),
			'email' => $email,
			'language' => $language,
			'registrationToken' => $registerToken,
			'idCommunity' => $idCommunity,
			'passwordSalt' => 'GRAH' //Placeholder for NOT NULLABLE field.
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
		if($password != '')
			$data['password'] = MD5($password);

		$this->db->where('idUser', $idUser);
		return $this->db->update('User', $data);
		//TODO: si user change de community, faut faire... d'zaffaires!!! ajouter un trigger dans BD si trop lourd.
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
}
