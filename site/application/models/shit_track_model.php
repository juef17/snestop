<?php
class Shit_Track_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Shit_Track($idUser = FALSE, $idTrack = FALSE) {
		$this->db->join('User', 'ShitTrack.idUser = User.idUser', 'inner');
		$this->db->join('Track', 'ShitTrack.idTrack = Track.idTrack', 'inner');
		if ($idUser === FALSE && $idTrack === FALSE) { // on n'a rien
			$query = $this->db->get('ShitTrack');
			return $query->result_array();
		} else { // on a les deux, yay!
			$this->db->where('ShitTrack.idUser', $idUser);
			$this->db->where('ShitTrack.idTrack', $idTrack);
			$query = $this->db->get('ShitTrack');
			return $query->row();
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_Shit_Track_for_user($idUser = FALSE) {
		$this->db->join('User', 'ShitTrack.idUser = User.idUser', 'inner');
		if($idUser === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('ShitTrack');
			return $query->result_array();
		} else {
			$this->db->where('ShitTrack.idUser', $idUser); 
			$query = $this->db->get('ShitTrack');
			return $query->result_array();
		}
	}
	
	public function get_Shit_Track_for_track($idTrack = FALSE) {
		$this->db->join('Track', 'ShitTrack.idTrack = Track.idTrack', 'inner');
		if($idTrack === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('ShitTrack');
			return $query->result_array();
		} else {
			$this->db->where('ShitTrack.idTrack', $idTrack); 
			$query = $this->db->get('ShitTrack');
			return $query->result_array();
		}
	}

	public function new_Shit_Track($idUser, $idTrack) {
		$data = array(
			'idUser' => $idUser,
			'idTrack' => $idTrack
		);
		return $this->db->insert('ShitTrack', $data);
	}

	public function delete_Shit_Track($idUser, $idTrack) {
		return $this->db->delete('ShitTrack', array('idUser' => $idUser, 'idTrack' => $idTrack));
	}
	
	public function get_number_of_Shit_Track_for_User($idUser) { // pour détecter des potentiels trolls
		$this->db->where('idUser', $idUser);
		$this->db->from('ShitTrack');
		return $this->db->count_all_results();
	}
	
	public function get_Shit_Track_ratio_for_User($idUser) { // pour détecter des potentiels trolls
		$this->db->where('idUser', $idUser);
		$this->db->from('DuelResult');
		$n = $this->db->count_all_results();
		if(!$n) return 0;
		return $this->get_number_of_Shit_Track_for_User($idUser) / $n;
	}
	
	public function could_the_user_be_a_troll($idUser) {
		$n = $this->get_number_of_Shit_Track_for_User($idUser);
		$r = $this->get_Shit_Track_ratio_for_User($idUser);
		if($n > 25 && $r == 1) return true;
		if($n > 50 && $r >= 0.95) return true;
		if($n > 100 && $r >= 0.90) return true;
		if($n > 200 && $r >= 0.85) return true;
		if($n > 400 && $r >= 0.80) return true;
		return false;
	}
	
	public function get_number_of_Shit_Track_for_Track($idTrack) { // pour détecter si une track est de la marde ou non
		$this->db->where('idTrack', $idTrack);
		$this->db->from('ShitTrack');
		return $this->db->count_all_results();
	}
	
	public function get_Shit_Track_ratio_for_Track($idTrack) { // pour détecter si une track est de la marde ou non
		$this->db->where('idTrackWon', $idTrack);
		$this->db->or_where('idTrackLost =', $idTrack); 
		$this->db->from('DuelResult');
		$n = $this->db->count_all_results();
		if(!$n) return 0;
		return $this->get_number_of_Shit_Track_for_Track($idTrack) / $n;
	}
	
	public function could_the_track_be_shit($idTrack) {
		$n = $this->get_number_of_Shit_Track_for_Track($idTrack);
		$r = $this->get_Shit_Track_ratio_for_User($idTrack);
		if($n >= 3 && $r == 1) return true;
		if($n >= 4 && $r >= 0.75) return true;
		if($n >= 5 && $r >= 0.6) return true;
		if($n >= 7 && $r >= 0.5) return true;
		if($n >= 10 && $r >= 0.40) return true;
		if($n >= 20 && $r >= 0.30) return true;
		if($n >= 30 && $r >= 0.2) return true;
		if($n >= 50 && $r >= 0.15) return true;
		if($n >= 100 && $r >= 0.1) return true;
		return false;
	}
}
