<?php
class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function login($username, $password) {
		$this->db->where('userName', $username);
		$this->db->where('password', MD5($password));
		$this->db->limit(1);

		$query = $this->db->get('User');
		if($query->num_rows() == 1)
			return $this->getUserFromRow($query->result()[0]);
		else
			return false;
	}

	public function remembered_login($token) {
		$this->db->where('rememberMeSnestopToken', $token);
		$this->db->limit(1);

		$query = $this->db->get('User');
		if($query->num_rows() == 1)
			return $this->getUserFromRow($query->result()[0]);
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

	public function get_users_list() {
		$this->db->select('idUser, userName, email, language, canStreamMP3, autoplay, isAdmin, enabled, Community.name as communityName');
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
}
