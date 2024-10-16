<?php namespace App\Models;

use CodeIgniter\Model;

class Duel_result_model extends Model
{
    protected $table = 'DuelResult';

	public function get_Duel_Result($idTrackWon = FALSE, $idTrackLost = FALSE, $idUser = FALSE) {
		$builder = $this->db->table('DuelResult');
		$builder->join('Track AS T1', 'DuelResult.idTrackWon = T1.idTrack', 'inner');
		$builder->join('Track AS T2', 'DuelResult.idTrackLost = T2.idTrack', 'inner');
		$builder->join('User', 'DuelResult.idUser = User.idUser', 'inner');
		if ($idTrackWon === FALSE && $idTrackLost === FALSE && $idUser === FALSE) {
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$builder->where('DuelResult.idTrackWon', $idTrackWon);
			$builder->where('DuelResult.idTrackLost', $idTrackLost);
			$builder->where('DuelResult.idUser', $idUser);
			$query = $builder->get();
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return row();
		} // si on a juste des infos partielles, voir les autres méthodes ci-bas
	}
	
	public function get_Duel_Result_for_User($idUser) {
		$builder = $this->db->table('DuelResult');
		$builder->select('GWon.idGame as idGameWon, GWon.titleEng as gameTitleWon, TWon.idTrack as idTrackWon, TWon.title as trackTitleWon, GLost.idGame as idGameLost, GLost.titleEng as gameTitleLost, TLost.idTrack as idTrackLost, TLost.title as trackTitleLost');
		$builder->join('Track AS TWon', 'DuelResult.idTrackWon = TWon.idTrack', 'inner');
		$builder->join('Game AS GWon', 'TWon.idGame = GWon.idGame', 'inner');
		$builder->join('Track AS TLost', 'DuelResult.idTrackLost = TLost.idTrack', 'inner');
		$builder->join('Game AS GLost', 'TLost.idGame = GLost.idGame', 'inner');
		$builder->where('DuelResult.idUser', $idUser);
		$query = $builder->get();
		return $query->getResult();
	}
	
	public function get_Duel_Result_for_Track($idTrack) {
		$builder = $this->db->table('DuelResult');
		$builder->join('Track AS T1', 'DuelResult.idTrackWon = T1.idTrack', 'inner');
		$builder->join('Track AS T2', 'DuelResult.idTrackLost = T2.idTrack', 'inner');
		$builder->where('DuelResult.idTrackWon', $idTrack); 
		$builder->orWhere('DuelResult.idTrackLost =', $idTrack); 
		$query = $builder->get();
		return $query->getResult('array');
	}

	public function new_Duel_Result($idTrackWon, $idTrackLost, $idUser) {
		$builder = $this->db->table('DuelResult');
		$data = array(
			'idTrackWon' => $idTrackWon,
			'idTrackLost' => $idTrackLost,
			'idUser' => $idUser
		);
		$builder->insert($data);
		return $builder->get();
	}
	
	public function get_number_of_duels_User($idUser) {
		$builder = $this->db->table('DuelResult');
		$builder->where('idUser', $idUser);
		return $builder->countAllResults();
	}
	
	public function get_number_of_duels_Track($idTrack) {
		$builder = $this->db->table('DuelResult');
		$builder->where('idTrackWon', $idTrack);
		$builder->orWhere('idTrackLost =', $idTrack);
		return $builder->countAllResults();
	}
	
	public function get_number_of_duels_won_Track($idTrack) {
		$builder = $this->db->table('DuelResult');
		$builder->where('idTrackWon', $idTrack);
		return $builder->countAllResults();
	}
	
	public function get_number_of_duels_lost_Track($idTrack) {
		$builder = $this->db->table('DuelResult');
		$builder->where('idTrackLost', $idTrack);
		return $builder->countAllResults();
	}

	public function delete_Duel_Result($idTrackWon, $idTrackLost, $idUser) {
		$builder = $this->db->table('DuelResult');
		$builder->delete(array('idUser' => $idUser, 'idTrackWon' => $idTrackWon, 'idTrackLost' => $idTrackLost));
		return $builder->get();
	}
}