<?php
class Game_Screenshot_Request_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Game_Screenshot_request($idGame = FALSE, $idUserRequester = FALSE) {
		$this->db->select('*, GameScreenshotRequest.screenshotUrl AS requestSreenshotUrl');
		$this->db->join('User', 'GameScreenshotRequest.idUserRequester = User.idUser', 'inner');
		$this->db->join('Game', 'GameScreenshotRequest.idGame = Game.idGame', 'inner');
		if ($idGame === FALSE && $idUserRequester === FALSE) { // on n'a rien
			$query = $this->db->get('GameScreenshotRequest');
			return $query->result();
		} else { // on a les deux, yay!
			$this->db->where('GameScreenshotRequest.idGame', $idGame);
			$this->db->where('GameScreenshotRequest.idUserRequester', $idUserRequester);
			$query = $this->db->get('GameScreenshotRequest');
			return $query->row();
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_GameScreenshotRequest_for_user($idUser) {
		$this->db->join('User', 'GameScreenshotRequest.idUserRequester = User.idUser', 'inner');
		$this->db->where('GameScreenshotRequest.idUserRequester', $idUser); 
		$query = $this->db->get('GameScreenshotRequest');
		return $query->result();
	}
	
	public function get_GameScreenshotRequest_for_game($idGame) {
		$this->db->join('Game', 'GameScreenshotRequest.idGame = Game.idGame', 'inner');
		$this->db->where('GameScreenshotRequest.idGame', $idGame); 
		$query = $this->db->get('GameScreenshotRequest');
		return $query->result();
	}

	public function set_Game_Screenshot_request($idGame, $idUserRequester, $screenshotURL) {
		$data = array(
			'idGame' => $idGame,
			'idUserRequester' => $idUserRequester,
			'screenshotURL' => $screenshotURL
		);

		return $this->db->insert('GameScreenshotRequest', $data);
	}

	public function delete_Game_Screenshot_request($idGame, $idUserRequester) {
		return $this->db->delete('GameScreenshotRequest', array('idGame' => $idGame, 'idUserRequester' => $idUserRequester));
	}
}
