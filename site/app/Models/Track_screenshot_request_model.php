<?php namespace App\Models;

use CodeIgniter\Model;

class Track_screenshot_request_model extends Model
{
    protected $table = 'TrackScreenshotRequest';
	
	public function get_Track_Screenshot_request($idTrack = FALSE, $idUserRequester = FALSE) {
		$builder = $this->db->table('TrackScreenshotRequest');
		$builder->select('*, TrackScreenshotRequest.screenshotUrl AS requestSreenshotUrl, CAST(Track.isScreenshotSet AS unsigned integer) AS isScreenshotSet');
		$builder->join('User', 'TrackScreenshotRequest.idUserRequester = User.idUser', 'inner');
		$builder->join('Track', 'TrackScreenshotRequest.idTrack = Track.idTrack', 'inner');
		$builder->join('Game', 'Track.idGame = Game.idGame', 'inner');
		if ($idTrack === FALSE && $idUserRequester === FALSE) { // on n'a rien
			$query = $builder->get();
			return $query->getResult();
		} else { // on a les deux, yay!
			$builder->where('TrackScreenshotRequest.idTrack', $idTrack);
			$builder->where('TrackScreenshotRequest.idUserRequester', $idUserRequester);
			$query = $builder->get();
			$row = null;
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_TrackScreenshotRequest_for_user($idUser) {
		$builder = $this->db->table('TrackScreenshotRequest');
		$builder->join('User', 'TrackScreenshotRequest.idUserRequester = User.idUser', 'inner');
		$builder->where('TrackScreenshotRequest.idUserRequester', $idUser); 
		$query = $builder->get();
		return $query->getResult();
	}
	
	public function get_TrackScreenshotRequest_for_track($idTrack) {
		$builder = $this->db->table('TrackScreenshotRequest');
		$builder->join('Track', 'TrackScreenshotRequest.idTrack = Track.idTrack', 'inner');
		$builder->where('TrackScreenshotRequest.idTrack', $idTrack); 
		$query = $builder->get();
		return $query->getResult();
	}

	public function set_Track_Screenshot_request($idTrack, $idUserRequester, $screenshotURL) {
		$data = array(
			'idTrack' => $idTrack,
			'idUserRequester' => $idUserRequester,
			'screenshotURL' => $screenshotURL
		);
		
		$builder = $this->db->table('TrackScreenshotRequest');
		if($this->get_Track_Screenshot_request($idTrack, $idUserRequester))
			$builder->delete(['idTrack' => $idTrack, 'idUserRequester' => $idUserRequester]);

		return $builder->insert($data);
	}

	public function delete_Track_Screenshot_request($idTrack, $idUserRequester) {
		$builder = $this->db->table('TrackScreenshotRequest');
		return $builder->delete(['idTrack' => $idTrack, 'idUserRequester' => $idUserRequester]);
	}
}
