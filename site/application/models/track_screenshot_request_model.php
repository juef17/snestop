<?php
class Track_Screenshot_Request_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Track_Screenshot_request($idTrack = FALSE, $idUserRequester = FALSE) {
		$this->db->select('*, TrackScreenshotRequest.screenshotUrl AS requestSreenshotUrl');
		$this->db->join('User', 'TrackScreenshotRequest.idUserRequester = User.idUser', 'inner');
		$this->db->join('Track', 'TrackScreenshotRequest.idTrack = Track.idTrack', 'inner');
		$this->db->join('Game', 'Track.idGame = Game.idGame', 'inner');
		if ($idTrack === FALSE && $idUserRequester === FALSE) { // on n'a rien
			$query = $this->db->get('TrackScreenshotRequest');
			return $query->result();
		} else { // on a les deux, yay!
			$this->db->where('TrackScreenshotRequest.idTrack', $idTrack);
			$this->db->where('TrackScreenshotRequest.idUserRequester', $idUserRequester);
			$query = $this->db->get('TrackScreenshotRequest');
			return $query->row();
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_TrackScreenshotRequest_for_user($idUser) {
		$this->db->join('User', 'TrackScreenshotRequest.idUserRequester = User.idUser', 'inner');
		$this->db->where('TrackScreenshotRequest.idUserRequester', $idUser); 
		$query = $this->db->get('TrackScreenshotRequest');
		return $query->result();
	}
	
	public function get_TrackScreenshotRequest_for_track($idTrack) {
		$this->db->join('Track', 'TrackScreenshotRequest.idTrack = Track.idTrack', 'inner');
		$this->db->where('TrackScreenshotRequest.idTrack', $idTrack); 
		$query = $this->db->get('TrackScreenshotRequest');
		return $query->result();
	}

	public function set_Track_Screenshot_request($idTrack, $idUserRequester, $screenshotURL) {
		$data = array(
			'idTrack' => $idTrack,
			'idUserRequester' => $idUserRequester,
			'screenshotURL' => $screenshotURL
		);
		
		if($this->get_Track_Screenshot_request($idTrack, $idUserRequester))
			$this->db->delete('TrackScreenshotRequest', array('idTrack' => $idTrack, 'idUserRequester' => $idUserRequester));

		return $this->db->insert('TrackScreenshotRequest', $data);
	}

	public function delete_Track_Screenshot_request($idTrack, $idUserRequester) {
		return $this->db->delete('TrackScreenshotRequest', array('idTrack' => $idTrack, 'idUserRequester' => $idUserRequester));
	}
}
