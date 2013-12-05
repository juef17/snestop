<?php
class User_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}

	function login($username, $password) {
		$this->db->select('idUser, userName, isAdmin');
		$this->db->from('User');
		$this->db->where('userName', $username);
		$this->db->where('password', MD5($password));
		$this->db->limit(1);

		$query = $this->db->get();
		if($query->num_rows() == 1) {
			$user = $query->result()[0];
			$user->isAdmin = (bool)$user->isAdmin;
			return $user;
		} else {
			return false;
		}
	}

	function remembered_login($token) {
		$this->db->select('userName');
		$this->db->from('User');
		$this->db->where('rememberMeSnestopToken', $token);
		$this->db->limit(1);

		$query = $this->db->get();
		if($query->num_rows() == 1)
			return $query->result();
		else
			return false;
	}

	public function set_token($username, $token) {
		$data = array(
			'rememberMeSnestopToken' => $token
		);
		$this->db->where('userName', $username);
		return $this->db->update('User', $data);
	}
}
