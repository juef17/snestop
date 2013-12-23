<?php
class Community_Request_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Community_request($idCommunityRequest = FALSE) {
		if ($idCommunityRequest === FALSE) {
			$query = $this->db->get('CommunityRequest');
			return $query->result();
		} else {
			$query = $this->db->get_where('CommunityRequest', array('idCommunityRequest' => $idCommunityRequest));
			return $query->row();
		}
	}

	public function get_Community_requests_for_dashboard() {
		$this->db->join('User', 'CommunityRequest.idUserRequester = User.idUser', 'inner');
		$query = $this->db->get('CommunityRequest');
		return $query->result();
	}

	public function set_Community_request($idUser, $URL, $name) {
		$data = array(
			'URL' => $URL,
			'name' => $name,
			'idUserRequester' => $idUser
		);

		return $this->db->insert('CommunityRequest', $data);
	}

	public function delete_Community_request($idCommunityRequest) {
		return $this->db->delete('CommunityRequest', array('idCommunityRequest' => $idCommunityRequest));
	}
}
