<?php
class Track_Request_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}

	public function get_track_request($idTrackRequest = FALSE) {
		if ($idTrackRequest === FALSE) {
			$query = $this->db->get('TrackRequest');
			return $query->result_array();
		} else {
			$query = $this->db->get_where('TrackRequest', array('idTrackRequest' => $idTrackRequest));
			return $query->row();
		}
	}

	public function set_track_request() {
		$this->load->helper('url');
		
		$slug = url_title($this->input->post('title'), 'dash', TRUE);
		
		$data = array(
			'title' => $this->input->post('title'),
			'slug' => $slug,
			'text' => $this->input->post('text')
		);

		return $this->db->insert('news', $data);
	}
}
