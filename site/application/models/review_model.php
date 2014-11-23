<?php
class Review_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Review($unapprovedOnly = FALSE, $idUser = FALSE, $idTrack = FALSE) {
		$this->db->join('User', 'Review.idUser = User.idUser', 'inner');
		$this->db->join('Track', 'Review.idTrack = Track.idTrack', 'inner');
		$this->db->join('Game', 'Track.idGame = Game.idGame', 'inner');
		if ($idUser === FALSE && $idTrack === FALSE) { // on n'a rien
			if($unapprovedOnly)
				$this->db->where('approved', 0);
				
			$query = $this->db->get('Review');

			$retval = array();
			foreach($query->result() as $row)
				$retval[] = $this->getReviewFromRow($row);
			return $retval;
		} else { // on a les deux, yay!
			$this->db->where('Review.idUser', $idUser);
			$this->db->where('Review.idTrack', $idTrack);
			$query = $this->db->get('Review');
			return $this->getReviewFromRow($query->row());
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_Review_for_user($idUse) {
		$this->db->join('User', 'Review.idUser = User.idUser', 'inner');
		$this->db->where('Review.idUser', $idUser); 
		$query = $this->db->get('Review');
		$retval = array();
		foreach($query->result() as $row)
			$retval[] = $this->getReviewFromRow($row);
		return $retval;
	}
	
	public function get_Review_for_track($idTrack) {
		$this->db->join('Track', 'Review.idTrack = Track.idTrack', 'inner');
		$this->db->where('Review.idTrack', $idTrack); 
		$query = $this->db->get('Review');
		$retval = array();
		foreach($query->result() as $row)
			$retval[] = $this->getReviewFromRow($row);
		return $retval;
	}

	private function getReviewFromRow($row) {
		$row->approved = ord($row->approved) == 1 || $row->approved == 1;
		return $row;
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
