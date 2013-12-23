<?php
class Mistake_Request_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Mistake_request($idMistakeRequest = FALSE) {
		if ($idMistakeRequest === FALSE) {
			$query = $this->db->get('MistakeRequest');
			return $query->result_array();
		} else {
			$query = $this->db->get_where('MistakeRequest', array('idMistakeRequest' => $idMistakeRequest));
			return $query->row();
		}
	}

	public function set_Mistake_request($idUserRequester, $text) {
		$data = array(
			'text' => $text,
			'idUserRequester' => $idUserRequester
		);

		return $this->db->insert('MistakeRequest', $data);
	}

	public function delete_Mistake_request($idMistakeRequest) {
		return $this->db->delete('MistakeRequest', array('idMistakeRequest' => $idMistakeRequest));
	}
}
