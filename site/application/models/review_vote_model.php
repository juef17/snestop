<?php
class Review_Vote_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Review_Vote($idUser = FALSE, $idReviewUser = FALSE, $idReviewTrack = FALSE) {
		$this->db->join('User', 'ReviewVote.idUser = User.idUser', 'inner');
		$this->db->join('Review', 'ReviewVote.idReviewUser = Review.idUser AND ReviewVote.idReviewTrack = Review.idTrack', 'inner');
		if ($idUser === FALSE && $idReviewTrack === FALSE && $idReviewUser === FALSE) { // on n'a rien
			$query = $this->db->get('ReviewVote');
			return $query->result_array();
		} else { // on a les 3, yay!
			$this->db->where('ReviewVote.idUser', $idUser);
			$this->db->where('ReviewVote.idReviewUser', $idReviewUser);
			$this->db->where('ReviewVote.idReviewTrack', $idReviewTrack);
			$query = $this->db->get('ReviewVote');
			return $query->row();
		} // si on a juste 1 des 3, voir les méthodes ci-bas
	}
	
	public function get_Review_Vote_for_User($idUser = FALSE) {
		$this->db->join('User', 'ReviewVote.idUser = User.idUser', 'inner');
		if($idUser === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('ReviewVote');
			return $query->result_array();
		} else {
			$this->db->where('ReviewVote.idUser', $idUser); 
			$query = $this->db->get('ReviewVote');
			return $query->result_array();
		}
	}
	
	public function get_Review_Vote_for_Review($idReviewUser = FALSE, $idReviewTrack = FALSE) {
		$this->db->join('Review', 'ReviewVote.idReviewUser = Review.idUser AND ReviewVote.idReviewTrack = Review.idTrack', 'inner');
		if($idReviewUser === FALSE || $idReviewTrack === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('ReviewVote');
			return $query->result_array();
		} else {
			$this->db->where('ReviewVote.idReviewUser', $idReviewUser); 
			$this->db->where('ReviewVote.idReviewTrack', $idReviewTrack); 
			$query = $this->db->get('ReviewVote');
			return $query->result_array();
		}
	}

	public function set_Review_Vote($idUser, $idReviewUser, $idReviewTrack, $voteType) {
		$data = array(
			'idUser' => $idUser,
			'idReviewUser' => $idReviewUser,
			'idReviewTrack' => $idReviewTrack,
			'voteType' => $voteType
		);

		return $this->db->insert('ReviewVote', $data);
	}

	public function delete_Review_Vote($idUser, $idReviewUser, $idReviewTrack) {
		return $this->db->delete('ReviewVote', array('idUser' => $idUser, 'idReviewUser' => $idReviewUser, 'idReviewTrack' => $idReviewTrack));
	}
}
