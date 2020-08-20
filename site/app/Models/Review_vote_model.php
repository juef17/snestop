<?php namespace App\Models;

use CodeIgniter\Model;

class Review_vote_model extends Model
{
    protected $table = 'ReviewVote';
	
	public function get_Review_Vote($idUser = FALSE, $idReviewUser = FALSE, $idReviewTrack = FALSE) {
		$builder = $this->db->table('ReviewVote');
		$builder->join('User', 'ReviewVote.idUser = User.idUser', 'inner');
		$builder->join('Review', 'ReviewVote.idReviewUser = Review.idUser AND ReviewVote.idReviewTrack = Review.idTrack', 'inner');
		if ($idUser === FALSE && $idReviewTrack === FALSE && $idReviewUser === FALSE) { // on n'a rien
			$query = $builder->get();
			return $query->getResult('array');
		} else { // on a les 3, yay!
			$builder->where('ReviewVote.idUser', $idUser);
			$builder->where('ReviewVote.idReviewUser', $idReviewUser);
			$builder->where('ReviewVote.idReviewTrack', $idReviewTrack);
			$query = $builder->get();
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		} // si on a juste 1 des 3, voir les méthodes ci-bas
	}
	
	public function get_Review_Vote_for_User($idUser = FALSE) {
		$builder = $this->db->table('ReviewVote');
		$builder->join('User', 'ReviewVote.idUser = User.idUser', 'inner');
		if($idUser === FALSE) { //devrait pas arriver...?
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$builder->where('ReviewVote.idUser', $idUser); 
			$query = $builder->get();
			return $query->getResult('array');
		}
	}
	
	public function get_Review_Vote_for_Review($idReviewUser = FALSE, $idReviewTrack = FALSE) {
		$builder = $this->db->table('ReviewVote');
		$builder->join('Review', 'ReviewVote.idReviewUser = Review.idUser AND ReviewVote.idReviewTrack = Review.idTrack', 'inner');
		if($idReviewUser === FALSE || $idReviewTrack === FALSE) { //devrait pas arriver...?
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$builder->where('ReviewVote.idReviewUser', $idReviewUser); 
			$builder->where('ReviewVote.idReviewTrack', $idReviewTrack); 
			$query = $builder->get();
			return $query->getResult('array');
		}
	}

	public function set_Review_Vote($idUser, $idReviewUser, $idReviewTrack, $voteType) {
		$data = array(
			'idUser' => $idUser,
			'idReviewUser' => $idReviewUser,
			'idReviewTrack' => $idReviewTrack,
			'voteType' => $voteType
		);

		$builder = $this->db->table('ReviewVote');
		return $builder->insert($data);
	}

	public function delete_Review_Vote($idUser, $idReviewUser, $idReviewTrack) {
		$builder = $this->db->table('ReviewVote');
		return $builder->delete(['idUser' => $idUser, 'idReviewUser' => $idReviewUser, 'idReviewTrack' => $idReviewTrack]);
	}
}
