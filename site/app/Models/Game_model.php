<?php namespace App\Models;

use CodeIgniter\Model;

class Game_model extends Model
{
    protected $table = 'Game';

	public function get_Game($idGame) {
		//TODO avant on faisait $idGame = mysql_real_escape_string($idGame); mais je pense que c'est escapé automatiquement par le query builder de CI...?
		$builder = $this->db->table('Game');
		$query = $builder->getWhere(['idGame' => $idGame]);
		foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
		if($row)
			return $this->getGameFromRow($row);
		else
			return null;
	}

	public function get_Games($page) {
		//TODO avant on faisait $page = mysql_real_escape_string($page); mais je pense que c'est escapé automatiquement par le query builder de CI...?
		$builder = $this->db->table('Game');
		$builder->orderBy('titleEng', 'asc');
		if(is_numeric($page)) {
			$builder->limit(50, ($page - 1) * 50);
		} elseif ($page === 'numbers') {
			$builder->where("titleEng regexp '^[^A-Za-z]'");
		} else {
			$builder->where("titleEng like '{$page}%'");
		}

		$query = $builder->get();
		$retval = array();
		foreach($query->getResult() as $row) {
			$retval[] = $this->getGameFromRow($row);
		}
		return $retval;
	}

	public function set_Game($titleJap, $titleEng, $rsnFileURL) {
		$builder = $this->db->table('Game');
		$data = array(
			'titleJap' => $titleJap,
			'titleEng' => $titleEng,
			'rsnFileURL' => $rsnFileURL
		);
		$builder->insert($data);
		return $builder->get();
	}

	public function setGameIsScreenshotSetFlag($idGame, $isScreenshotSet) {
		$builder = $this->db->table('Game');
		$builder->where('Game.idGame', $idGame);
		return $builder->update(['isScreenshotSet' => $isScreenshotSet]);
	}

	public function delete_Game($idGame) {
		$builder = $this->db->table('Game');
		return $builder->delete(['idGame' => $idGame]);
	}
	
	public function update_Game($idGame, $titleJap, $titleEng, $rsnFileURL) {
		$builder = $this->db->table('Game');
		$builder->where('Game.idGame', $idGame);
		return $builder->update(['titleJap' => $titleJap, 'titleEng' => $titleEng, 'rsnFileURL' => $rsnFileURL]);
	}

	public function get_nb_pages() {
		$builder = $this->db->table('Game');
		return ceil($builder->countAllResults() / 50);
	}

	private function getGameFromRow($row) {
		$row->isScreenshotSet = ord($row->isScreenshotSet) == 1 || $row->isScreenshotSet == 1;
		return $row;
	}

	public function switchTitles_Game($idGame) {
		$builder = $this->db->table('Game');
		$builder->where('Game.idGame', $idGame);
		if($builder->countAllResults(false) == 1){
			$query = $builder->get();
			foreach ($query->getResult() as $game) break; //TODO esti que c'est lette
			$builder = $this->db->table('Game');
			$builder->where('Game.idGame', $idGame);
			return $builder->update(['titleJap' => $game->titleEng, 'titleEng' => $game->titleJap]);
		}
	}

	public function search($searchString) {
		$builder = $this->db->table('Game');
		foreach(explode(' ', $searchString) as $word) {
			$word = preg_quote($word);
			$regex = $this->db->escapeLikeString("({$word})+");
			$builder->where("(titleEng RLIKE '{$regex}' OR titleJap RLIKE '{$regex}')");
		}

		$builder->limit(150);
		$builder->orderBy('titleEng');
		$query = $builder->get();

		$retval = array();
		foreach($query->getResult() as $row) {
			$retval[] = $this->getGameFromRow($row);
		}
		return $retval;
	}
}