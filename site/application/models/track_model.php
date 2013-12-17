<?php
class Track_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Track($idTrack = FALSE) {
		if ($idTrack === FALSE) {
			$query = $this->db->get('Track');
			return $query->result_array();
		} else {
			$query = $this->db->get_where('Track', array('idTrack' => $idTrack));
			return $query->row();
		}
	}

	public function get_Track_for_Game($idGame = FALSE) {
		if ($idGame === FALSE) { // devrait pas arriver...
			$query = $this->db->get('Track');
			return $query->result_array();
		} else {
			$query = $this->db->get_where('Track', array('idGame' => $idGame));
			return $query->result_array();
		}
	}

	public function set_Track($idGame, $title, $length, $fadeLength, $composer, $turnedOffByAdmin, $screenshotURL, $isJingle, $spcURL, $spcEncodedURL) {
		$data = array(
			'idGame' => $idGame,
			'title' => $title,
			'length' => $length,
			'fadeLength' => $fadeLength,
			'composer' => $composer,
			'turnedOffByAdmin' => $turnedOffByAdmin,
			'screenshotURL' => $screenshotURL,
			'isJingle' => $isJingle,
			'glicko2RD' => 350,
			'glicko2rating' => 1500,
			'glicko2sigma' => 0.06,
			'eloRating' => 1600,
			'spcURL' => $spcURL,
			'spcEncodedURL' => $spcEncodedURL
		);
		return $this->db->insert('Track', $data);
	}

	public function delete_Track($idTrack) {
		return $this->db->delete('Track', array('idTrack' => $idTrack));
	}

	public function turnOn_Track($idTrack) {
		$this->db->where('Track.idTrack', $idTrack);
		return $this->db->update('Track', array('turnedOffByAdmin' => FALSE));
	}

	public function turnOff_Track($idTrack) {
		$this->db->where('Track.idTrack', $idTrack);
		return $this->db->update('Track', array('turnedOffByAdmin' => TRUE));
	}
	
	public function update_Track($idTrack, $idGame, $title, $length, $fadeLength, $composer, $turnedOffByAdmin, $screenshotURL, $isJingle, $spcURL, $spcEncodedURL) {
		$this->db->where('Track.idTrack', $idTrack);
		$data = array(
			'idGame' => $idGame,
			'title' => $title,
			'length' => $length,
			'fadeLength' => $fadeLength,
			'composer' => $composer,
			'turnedOffByAdmin' => $turnedOffByAdmin,
			'screenshotURL' => $screenshotURL,
			'isJingle' => $isJingle,
			'spcURL' => $spcURL,
			'spcEncodedURL' => $spcEncodedURL
		);
		return $this->db->update('Track', $data);
	}
}
