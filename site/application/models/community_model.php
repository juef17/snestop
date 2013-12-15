<?php
class Community_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Community($idCommunity = FALSE) {
		if ($idCommunity === FALSE) {
			$query = $this->db->get('Community');
			return $query->result_array();
		} else {
			$query = $this->db->get_where('Community', array('idCommunity' => $idCommunity));
			return $query->row();
		}
	}

	public function set_Community($name, $token) {
		$data = array(
			'name' => $name,
			'token' => $token
		);
		return $this->db->insert('Community', $data);
	}

	public function delete_Community($idCommunity) {
		return $this->db->delete('Community', array('idCommunity' => $idCommunity));
	}
	
	public function update_Community($idCommunity, $name, $token) {
		$this->db->where('Community.idCommunity', $idCommunity);
		return $this->db->update('Community', array('name' => $name, 'token' => $token));
	}
}
