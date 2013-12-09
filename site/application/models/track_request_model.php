<?php
class Track_Request_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_track_request($idTrackRequest) {
		if ($idTrackRequest === FALSE) {
			$query = $this->db->get('TrackRequest');
			return $query->result_array();
		} else {
			$query = $this->db->get_where('TrackRequest', array('idTrackRequest' => $idTrackRequest));
			return $query->row();
		}
	}

	public function set_track_request($idUser, $game, $title, $trackUrl) {
		$data = array(
			'idUserRequester' => $idUser,
			'game' => $game,
			'title' => $title,
			'trackURL' => $trackUrl
		);

		return $this->db->insert('TrackRequest', $data);
	}
}
