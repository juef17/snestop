<?php
class Review_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Review($idUser = FALSE, $idTrack = FALSE) {
		$this->db->join('User', 'Review.idUser = User.idUser', 'inner');
		$this->db->join('Track', 'Review.idTrack = Track.idTrack', 'inner');
		if ($idUser === FALSE && $idTrack === FALSE) { // on n'a rien
			$query = $this->db->get('Review');
			return $query->result_array();
		} else { // on a les deux, yay!
			$this->db->where('Review.idUser', $idUser);
			$this->db->where('Review.idTrack', $idTrack);
			$query = $this->db->get('Review');
			return $query->row();
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_Review_for_user($idUser = FALSE) {
		$this->db->join('User', 'Review.idUser = User.idUser', 'inner');
		if($idUser === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('Review');
			return $query->result_array();
		} else {
			$this->db->where('Review.idUser', $idUser); 
			$query = $this->db->get('Review');
			return $query->result_array();
		}
	}
	
	public function get_Review_for_track($idTrack = FALSE) {
		$this->db->join('Track', 'Review.idTrack = Track.idTrack', 'inner');
		if($idTrack === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('Review');
			return $query->result_array();
		} else {
			$this->db->where('Review.idTrack', $idTrack); 
			$query = $this->db->get('Review');
			return $query->result_array();
		}
	}

	public function set_Review($idUser, $idTrack, $text) {
		$data = array(
			'idUser' => $idUser,
			'idTrack' => $idTrack,
			'text' => $text,
			'approved' => FALSE
		);
		return $this->db->insert('Review', $data);
	}

	public function delete_Review($idUser, $idTrack) {
		return $this->db->delete('Review', array('idUser' => $idUser, 'idTrack' => $idTrack));
	}
	
	public function approve_Review($idUser, $idTrack) {
		$this->db->where('Review.idUser', $idUser);
		$this->db->where('Review.idTrack', $idTrack);
		return $this->db->update('Review', array('approved' => TRUE));
	}
	
	public function disapprove_Review($idUser, $idTrack) {
		$this->db->where('Review.idUser', $idUser);
		$this->db->where('Review.idTrack', $idTrack);
		return $this->db->update('Review', array('approved' => FALSE));
	}
	
	public function update_Review($idUser, $idTrack, $text) {
		$this->db->where('Review.idUser', $idUser);
		$this->db->where('Review.idTrack', $idTrack);
		return $this->db->update('Review', array('text' => $text));
	}
	
	public function get_Review_score($idReviewUser, $idReviewTrack) {
		$this->db->where('idReviewUser', $idReviewUser);
		$this->db->where('idReviewTrack', $idReviewTrack);
		$this->db->where('voteType', 1);
		$this->db->from('ReviewVote');
		$upVotes = $this->db->count_all_results();
		$this->db->where('idReviewUser', $idReviewUser);
		$this->db->where('idReviewTrack', $idReviewTrack);
		$this->db->where('voteType', -1);
		$this->db->from('ReviewVote');
		$downVotes = $this->db->count_all_results();
		return ($upVotes - $downVotes);
	}
}