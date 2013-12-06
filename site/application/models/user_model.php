<?php
class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function login($username, $password) {
		$this->db->select('idUser, userName, isAdmin');
		$this->db->from('User');
		$this->db->where('userName', $username);
		$this->db->where('password', MD5($password));
		$this->db->limit(1);

		$query = $this->db->get();
		if($query->num_rows() == 1)
			return $this->getUserFromRow($query->result()[0]);
		else
			return false;
	}

	public function remembered_login($token) {
		$this->db->select('idUser, userName, isAdmin');
		$this->db->from('User');
		$this->db->where('rememberMeSnestopToken', $token);
		$this->db->limit(1);

		$query = $this->db->get();
		if($query->num_rows() == 1)
			return $this->getUserFromRow($query->result()[0]);
		else
			return false;
	}

	private function getUserFromRow($row) {
		$row->isAdmin = (bool)$row->isAdmin;
		return $row;
	}

	public function set_token($username, $token) {
		$data = array(
			'rememberMeSnestopToken' => $token
		);
		$this->db->where('userName', $username);
		return $this->db->update('User', $data);
	}
}
