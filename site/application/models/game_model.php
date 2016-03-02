<?php
class Game_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Game($idGame) {
		$idGame = mysql_real_escape_string($idGame);
		$query = $this->db->get_where('Game', array('idGame' => $idGame));
		if($row = $query->row())
			return $this->getGameFromRow($row);
		else
			return null;
	}

	public function get_Games($page) {
		$page = mysql_real_escape_string($page);
		$this->db->order_by('titleEng', 'asc');
		if(is_numeric($page)) {
			$this->db->limit(50, ($page - 1) * 50);
		} elseif ($page === 'numbers') {
			$this->db->where("titleEng regexp '^[^A-Za-z]'");
		} else {
			$this->db->where("titleEng like '{$page}%'");
		}

		$query = $this->db->get('Game');
		$retval = array();
		foreach($query->result() as $row) {
			$retval[] = $this->getGameFromRow($row);
		}
		return $retval;
	}

	public function set_Game($titleJap, $titleEng, $rsnFileURL) {
		$data = array(
			'titleJap' => $titleJap,
			'titleEng' => $titleEng,
			'rsnFileURL' => $rsnFileURL
		);
		return $this->db->insert('Game', $data);
	}

	public function setGameIsScreenshotSetFlag($idGame, $isScreenshotSet) {
		$this->db->where('Game.idGame', $idGame);
		return $this->db->update('Game', array('isScreenshotSet' => $isScreenshotSet));
	}

	public function delete_Game($idGame) {
		return $this->db->delete('Game', array('idGame' => $idGame));
	}
	
	public function update_Game($idGame, $titleJap, $titleEng, $rsnFileURL) {
		$this->db->where('Game.idGame', $idGame);
		return $this->db->update('Game', array('titleJap' => $titleJap, 'titleEng' => $titleEng, 'rsnFileURL' => $rsnFileURL));
	}

	public function get_nb_pages() {
		return ceil($this->db->count_all('Game') / 50);
	}

	private function getGameFromRow($row) {
		$row->isScreenshotSet = ord($row->isScreenshotSet) == 1 || $row->isScreenshotSet == 1;
		return $row;
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

	public function search($searchString) {
		foreach(explode(' ', $searchString) as $word) {
			$word = preg_quote($word);
			$regex = $this->db->escape_str("({$word})+");
			$this->db->where("(titleEng RLIKE '{$regex}' OR titleJap RLIKE '{$regex}')");
		}

		$this->db->limit(150);
		$this->db->order_by('titleEng');
		$query = $this->db->get('Game');

		$retval = array();
		foreach($query->result() as $row) {
			$retval[] = $this->getGameFromRow($row);
		}
		return $retval;
	}
}
