<?php namespace App\Models;

use CodeIgniter\Model;

class Game_screenshot_request_model extends Model
{
    protected $table = 'GameScreenshotRequest';
	
	public function get_Game_Screenshot_request($idGame = FALSE, $idUserRequester = FALSE) {
		$builder = $this->db->table('GameScreenshotRequest');
		$builder->select('*, GameScreenshotRequest.screenshotUrl AS requestSreenshotUrl, CAST(isScreenshotSet AS unsigned integer) AS isScreenshotSet');
		$builder->join('User', 'GameScreenshotRequest.idUserRequester = User.idUser', 'inner');
		$builder->join('Game', 'GameScreenshotRequest.idGame = Game.idGame', 'inner');
		if ($idGame === FALSE && $idUserRequester === FALSE) { // on n'a rien
			$query = $builder->get();
			return $query->getResult();
		} else { // on a les deux, yay!
			$builder->where('GameScreenshotRequest.idGame', $idGame);
			$builder->where('GameScreenshotRequest.idUserRequester', $idUserRequester);
			$query = $builder->get();
			$row = null;
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_GameScreenshotRequest_for_user($idUser) {
		$builder = $this->db->table('GameScreenshotRequest');
		$builder->join('User', 'GameScreenshotRequest.idUserRequester = User.idUser', 'inner');
		$builder->where('GameScreenshotRequest.idUserRequester', $idUser); 
		$query = $builder->get();
		return $query->getResult();
	}
	
	public function get_GameScreenshotRequest_for_game($idGame) {
		$builder = $this->db->table('GameScreenshotRequest');
		$builder->join('Game', 'GameScreenshotRequest.idGame = Game.idGame', 'inner');
		$builder->where('GameScreenshotRequest.idGame', $idGame); 
		$query = $builder->get();
		return $query->getResult();
	}

	public function set_Game_Screenshot_request($idGame, $idUserRequester, $screenshotURL) {
		$data = array(
			'idGame' => $idGame,
			'idUserRequester' => $idUserRequester,
			'screenshotURL' => $screenshotURL
		);
		
		$builder = $this->db->table('GameScreenshotRequest');
		if($this->get_Game_Screenshot_request($idGame, $idUserRequester))
			$builder->delete(['idGame' => $idGame, 'idUserRequester' => $idUserRequester]);

		return $builder->insert($data);
	}

	public function delete_Game_Screenshot_request($idGame, $idUserRequester) {
		$builder = $this->db->table('GameScreenshotRequest');
		return $builder->delete(['idGame' => $idGame, 'idUserRequester' => $idUserRequester]);
	}
}
