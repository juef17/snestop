<?php namespace App\Models;

use CodeIgniter\Model;

class Review_model extends Model
{
    protected $table = 'Review';
	
	public function get_Review($unapprovedOnly = FALSE, $idUser = FALSE, $idTrack = FALSE) {
		$builder = $this->db->table('Review');
		$builder->join('User', 'Review.idUser = User.idUser', 'inner');
		$builder->join('Track', 'Review.idTrack = Track.idTrack', 'inner');
		$builder->join('Game', 'Track.idGame = Game.idGame', 'inner');
		if ($idUser === FALSE && $idTrack === FALSE) { // on n'a rien
			if($unapprovedOnly)
				$builder->where('approved', 0);
				
			$query = $builder->get();

			$retval = array();
			foreach($query->getResult() as $row)
				$retval[] = $this->getReviewFromRow($row);
			return $retval;
		} else { // on a les deux, yay!
			$builder->where('Review.idUser', $idUser);
			$builder->where('Review.idTrack', $idTrack);
			$query = $builder->get();
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $this->getReviewFromRow($row);
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_Review_for_user($idUser, $approvedOnly = TRUE) {
		$builder = $this->db->table('Review');
		$builder->select('Review.*, Track.title, Game.idGame, Game.titleEng');
		$builder->join('Track', 'Review.idTrack = Track.idTrack', 'inner');
		$builder->join('Game', 'Track.idGame = Game.idGame', 'inner');
		$builder->where('Review.idUser', $idUser);
		if($approvedOnly)
			$builder->where('Review.approved', 1);
		$query = $builder->get();
		$retval = array();
		foreach($query->getResult() as $row)
			$retval[] = $this->getReviewFromRow($row);
		return $retval;
	}
	
	public function get_Review_for_track($idTrack, $approvedOnly = TRUE) {
		$builder = $this->db->table('Review');
		$builder->select('Review.*, Track.idTrack, User.userName');
		$builder->join('Track', 'Review.idTrack = Track.idTrack', 'inner');
		$builder->join('User', 'Review.idUser = User.idUser', 'inner');
		$builder->where('Review.idTrack', $idTrack);
		if($approvedOnly)
			$builder->where('Review.approved', 1);
		$query = $builder->get();
		$retval = array();
		foreach($query->getResult() as $row)
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
		$builder = $this->db->table('Review');
		return $builder->insert($data);
	}

	public function delete_Review($idUser, $idTrack) {
		$builder = $this->db->table('Review');
		return $builder->delete(['idUser' => $idUser, 'idTrack' => $idTrack]);
	}
	
	public function approve_Review($idUser, $idTrack) {
		$builder = $this->db->table('Review');
		$builder->where('Review.idUser', $idUser);
		$builder->where('Review.idTrack', $idTrack);
		return $builder->update(['approved' => TRUE]);
	}
	
	public function disapprove_Review($idUser, $idTrack) {
		$builder = $this->db->table('Review');
		$builder->where('Review.idUser', $idUser);
		$builder->where('Review.idTrack', $idTrack);
		return $builder->update(['approved' => FALSE]);
	}
	
	public function update_Review($idUser, $idTrack, $text) {
		$builder = $this->db->table('Review');
		$builder->where('Review.idUser', $idUser);
		$builder->where('Review.idTrack', $idTrack);
		return $builder->update(['text' => $text]);
	}
	
	public function get_Review_score($idReviewUser, $idReviewTrack) {
		$builder = $this->db->table('ReviewVote');
		$builder->where('idReviewUser', $idReviewUser);
		$builder->where('idReviewTrack', $idReviewTrack);
		$builder->where('voteType', 1);
		$upVotes = $builder->countAllResults();
		$builder->where('idReviewUser', $idReviewUser);
		$builder->where('idReviewTrack', $idReviewTrack);
		$builder->where('voteType', -1);
		$downVotes = $builder->countAllResults();
		return ($upVotes - $downVotes);
	}
}
