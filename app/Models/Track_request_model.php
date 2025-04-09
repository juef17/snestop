<?php namespace App\Models;

use CodeIgniter\Model;

class Track_request_model extends Model
{
    protected $table = 'TrackRequest';
	
	public function get_track_request($idTrackRequest = FALSE) {
		$builder = $this->db->table('TrackRequest');
		$builder->join('User', 'TrackRequest.idUserRequester = User.idUser', 'inner');
		if ($idTrackRequest === FALSE) {
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$query = $builder->getWhere(['idTrackRequest' => $idTrackRequest]);
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		}
	}

	public function set_track_request($idUser, $game, $title, $trackUrl) {
		$data = array(
			'idUserRequester' => $idUser,
			'game' => $game,
			'title' => $title,
			'trackURL' => $trackUrl
		);

		$builder = $this->db->table('TrackRequest');
		return $builder->insert($data);
	}

	public function delete_track_request($idTrackRequest) {
		$builder = $this->db->table('TrackRequest');
		return $builder->delete(['idTrackRequest' => $idTrackRequest]);
	}
}
