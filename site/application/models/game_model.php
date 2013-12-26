<?php
class Game_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Game($idGame) {
		$query = $this->db->get_where('Game', array('idGame' => $idGame));
		return $query->row();
	}

	public function get_Games($page) {
		$this->db->order_by('titleEng', 'asc');
		$this->db->limit(50, ($page - 1) * 50);
		$query = $this->db->get('Game');
		return $query->result();
	}

	public function set_Game($titleJap, $titleEng, $screenshotURL, $rsnFileURL) {
		$data = array(
			'titleJap' => $titleJap,
			'titleEng' => $titleEng,
			'screenshotURL' => $screenshotURL,
			'rsnFileURL' => $rsnFileURL
		);
		return $this->db->insert('Game', $data);
	}

	public function delete_Game($idGame) {
		return $this->db->delete('Game', array('idGame' => $idGame));
	}
	
	public function update_Game($idGame, $titleJap, $titleEng, $screenshotURL, $rsnFileURL) {
		$this->db->where('Game.idGame', $idGame);
		return $this->db->update('Game', array('titleJap' => $titleJap, 'titleEng' => $titleEng, 'screenshotURL' => $screenshotURL, 'rsnFileURL' => $rsnFileURL));
	}

	public function get_nb_pages() {
		return ceil($this->db->count_all('Game') / 50);
	}

	public function switchTitles_Game($idGame) {
		$this->db->where('Game.idGame', $idGame);
		$query = $this->db->get('Game');
		if($query->num_rows() == 1) {
			$game = $query->row();
			$this->db->where('Game.idGame', $idGame);
			return $this->db->update('Game', array('titleJap' => $game->titleEng, 'titleEng' => $game->titleJap));
		}
	}
}
