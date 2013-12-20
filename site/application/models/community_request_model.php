<?php
class Community_Request_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Community_request($idCommunityRequest = FALSE) {
		if ($idCommunityRequest === FALSE) {
			$query = $this->db->get('CommunityRequest');
			return $query->result_array();
		} else {
			$query = $this->db->get_where('CommunityRequest', array('idCommunityRequest' => $idCommunityRequest));
			return $query->row();
		}
	}

	public function set_Community_request($URL, $name, $emailRequester) {
		$data = array(
			'URL' => $URL,
			'name' => $name,
			'emailRequester' => $emailRequester
		);

		return $this->db->insert('CommunityRequest', $data);
	}

	public function delete_Community_request($idCommunityRequest) {
		return $this->db->delete('CommunityRequest', array('idCommunityRequest' => $idCommunityRequest));
	}
}
