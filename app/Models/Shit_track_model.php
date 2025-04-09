<?php namespace App\Models;

use CodeIgniter\Model;

class Shit_track_model extends Model
{
    protected $table = 'ShitTrack';
	
	public function get_Shit_Track($idUser = FALSE, $idTrack = FALSE) {
		$builder = $this->db->table('ShitTrack');
		$builder->join('User', 'ShitTrack.idUser = User.idUser', 'inner');
		$builder->join('Track', 'ShitTrack.idTrack = Track.idTrack', 'inner');
		if ($idUser === FALSE && $idTrack === FALSE) { // on n'a rien
			$query = $builder->get();
			return $query->getResult('array');
		} else { // on a les deux, yay!
			$builder->where('ShitTrack.idUser', $idUser);
			$builder->where('ShitTrack.idTrack', $idTrack);
			$query = $builder->get();
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_Shit_Track_for_user($idUser = FALSE) {
		$builder = $this->db->table('ShitTrack');
		$builder->join('User', 'ShitTrack.idUser = User.idUser', 'inner');
		if($idUser === FALSE) { //devrait pas arriver...?
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$builder->where('ShitTrack.idUser', $idUser); 
			$query = $builder->get();
			return $query->getResult('array');
		}
	}
	
	public function get_Shit_Track_for_track($idTrack = FALSE) {
		$builder = $this->db->table('ShitTrack');
		$builder->join('Track', 'ShitTrack.idTrack = Track.idTrack', 'inner');
		if($idTrack === FALSE) { //devrait pas arriver...?
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$builder->where('ShitTrack.idTrack', $idTrack); 
			$query = $builder->get();
			return $query->getResult('array');
		}
	}

	public function new_Shit_Track($idUser, $idTrack) {
		$data = array(
			'idUser' => $idUser,
			'idTrack' => $idTrack
		);
		$builder = $this->db->table('ShitTrack');
		return $builder->insert($data);
	}

	public function delete_Shit_Track($idUser, $idTrack) {
		$builder = $this->db->table('ShitTrack');
		return $builder->delete(['idUser' => $idUser, 'idTrack' => $idTrack]);
	}
	
	public function get_number_of_Shit_Track_for_User($idUser) { // pour détecter des potentiels trolls
		$builder = $this->db->table('ShitTrack');
		$builder->where('idUser', $idUser);
		return $builder->countAllResults();
	}
	
	public function get_Shit_Track_ratio_for_User($idUser) { // pour détecter des potentiels trolls
		$builder = $this->db->table('DuelResult');
		$builder->where('idUser', $idUser);
		$n = $builder->countAllResults();
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
		$builder = $this->db->table('ShitTrack');
		$builder->where('idTrack', $idTrack);
		return $builder->countAllResults();
	}
	
	public function get_Shit_Track_ratio_for_Track($idTrack) { // pour détecter si une track est de la marde ou non
		$builder = $this->db->table('DuelResult');
		$builder->where('idTrackWon', $idTrack);
		$builder->orWhere('idTrackLost =', $idTrack); 
		$n = $builder->countAllResults();
		if(!$n) return 0;
		return $this->get_number_of_Shit_Track_for_Track($idTrack) / $n;
	}
	
	public function could_the_track_be_shit($idTrack) {
		$n = $this->get_number_of_Shit_Track_for_Track($idTrack);
		$r = $this->get_Shit_Track_ratio_for_Track($idTrack);
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
