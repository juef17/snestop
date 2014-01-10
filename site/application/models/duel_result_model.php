<?php
class Duel_Result_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Duel_Result($idTrackWon = FALSE, $idTrackLost = FALSE, $idUser = FALSE) {
		$this->db->join('Track AS T1', 'DuelResult.idTrackWon = T1.idTrack', 'inner');
		$this->db->join('Track AS T2', 'DuelResult.idTrackLost = T2.idTrack', 'inner');
		$this->db->join('User', 'DuelResult.idUser = User.idUser', 'inner');
		if ($idTrackWon === FALSE && $idTrackLost === FALSE && $idUser === FALSE) {
			$query = $this->db->get('DuelResult');
			return $query->result_array();
		} else {
			$this->db->where('DuelResult.idTrackWon', $idTrackWon);
			$this->db->where('DuelResult.idTrackLost', $idTrackLost);
			$this->db->where('DuelResult.idUser', $idUser);
			$query = $this->db->get('DuelResult');
			return $query->row();
		} // si on a juste des infos partielles, voir les autres méthodes ci-bas
	}
	
	public function get_Duel_Result_for_User($idUser = FALSE) {
		$this->db->join('User', 'DuelResult.idUser = User.idUser', 'inner');
		if($idUser === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('DuelResult');
			return $query->result_array();
		} else {
			$this->db->where('DuelResult.idUser', $idUser); 
			$query = $this->db->get('DuelResult');
			return $query->result_array();
		}
	}
	
	public function get_Duel_Result_for_Track($idTrack = FALSE) {
		$this->db->join('Track AS T1', 'DuelResult.idTrackWon = T1.idTrack', 'inner');
		$this->db->join('Track AS T2', 'DuelResult.idTrackLost = T2.idTrack', 'inner');
		if($idTrack === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('DuelResult');
			return $query->result_array();
		} else {
			$this->db->where('DuelResult.idTrackWon', $idTrack); 
			$this->db->or_where('DuelResult.idTrackLost =', $idTrack); 
			$query = $this->db->get('DuelResult');
			return $query->result_array();
		}
	}

	public function new_Duel_Result($idTrackWon, $idTrackLost, $idUser) {
		$data = array(
			'idTrackWon' => $idTrackWon,
			'idTrackLost' => $idTrackLost,
			'idUser' => $idUser
		);
		return $this->db->insert('DuelResult', $data);
	}
	
	public function get_number_of_duels_User($idUser) {
		$this->db->where('idUser', $idUser);
		$this->db->from('DuelResult');
		return $this->db->count_all_results();
	}
	
	public function get_number_of_duels_Track($idTrack) {
		$this->db->where('idTrackWon', $idTrack);
		$this->db->or_where('idTrackLost =', $idTrack); 
		$this->db->from('DuelResult');
		return $this->db->count_all_results();
	}
	
	public function get_number_of_duels_won_Track($idTrack) {
		$this->db->where('idTrackWon', $idTrack);
		$this->db->from('DuelResult');
		return $this->db->count_all_results();
	}
	
	public function get_number_of_duels_lost_Track($idTrack) {
		$this->db->where('idTrackLost', $idTrack);
		$this->db->from('DuelResult');
		return $this->db->count_all_results();
	}

	public function delete_Duel_Result($idTrackWon, $idTrackLost, $idUser) {
		return $this->db->delete('DuelResult', array('idUser' => $idUser, 'idTrackWon' => $idTrackWon, 'idTrackLost' => $idTrackLost));
	}
}
