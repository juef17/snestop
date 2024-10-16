<?php namespace App\Models;

use CodeIgniter\Model;

class Track_model extends Model
{
    protected $table = 'Track';
	
	public function get_Track($idTrack) {
		$builder = $this->db->table('Track');
		$builder->select('Game.titleEng AS gameTitleEng, Game.isScreenshotSet as gameIsScreenshotSet, Track.*');
		$builder->join('Game', 'Track.idGame = Game.idGame', 'inner');
		$builder->where('idTrack', $idTrack);
		$query = $builder->get();
		foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
		if($row) {
			$row->gameIsScreenshotSet = ord($row->gameIsScreenshotSet) == 1 || $row->gameIsScreenshotSet == 1;
			return $this->getTrackFromRow($row);
		} else {
			return null;
		}
	}

	public function get_Track_spc_url($idTrack) {
		$builder = $this->db->table('Track');
		$builder->select('spcURL');
		$builder->where('idTrack', $idTrack);
		$query = $builder->get();
		$query = $builder->getWhere(['idTrack' => $idTrack]);
		foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
		if($row)
			return $row->spcURL;
		else
			return null;
	}

	public function getTracksForPlaylist($idTracks) {
		$builder = $this->db->table('Track');
		$builder->select('Track.idTrack, Track.title, Track.length, Track.isScreenshotSet, Game.idGame, Game.titleEng AS gameTitleEng');
		$builder->join('Game', 'Track.idGame = Game.idGame', 'INNER');
		$builder->whereIn('Track.idTrack', $idTracks);
		$builder->orderBy('trackNumber', 'asc');
		$query = $builder->get('Track');
		$retval = array();

		$i = 0;
		foreach($query->getResult() as $row) {
			$row->isScreenshotSet = ord($row->isScreenshotSet) == 1 || $row->isScreenshotSet == 1;
			$row->position = $i++;
			$retval[] = $row;
		}
		
		return $retval;
	}

	public function get_Tracks_for_Game($idGame, $showNormalTracks, $showJingles, $showSfx, $showVfx) {
		$orClauses = array();
		
		if($showNormalTracks)
			$orClauses[] = '(isJingle = 0 AND isSoundEffect = 0 AND isVoice = 0)';
			
		if($showJingles)
			$orClauses[] = 'isJingle = 1';
			
		if($showSfx)
			$orClauses[] = 'isSoundEffect = 1';
			
		if($showVfx)
			$orClauses[] = 'isVoice = 1';

		if(count($orClauses) == 0)
			$orClauses[] = '1 = 0'; //retourner rien

		$builder = $this->db->table('Track');
		$builder->orWhere('(' . implode(' OR ', $orClauses) . ')');
		$query = $builder->getWhere(['idGame' => $idGame]);

		$retval = array();
		foreach($query->getResult() as $row) {
			$retval[] = $this->getTrackFromRow($row);
		}
		return $retval;
	}

	public function get_new_Track($idGame) {
		$data = array(
			'idTrack' => 0,
			'idGame' => $idGame,
			'title' => '',
			'length' => 0,
			'composer' => '',
			'turnedOffByAdmin' => FALSE,
			'isScreenshotSet' => 0,
			'isJingle' => FALSE,
			'glicko2RD' => 350,
			'glicko2rating' => 1500,
			'glicko2sigma' => 0.06,
			'eloRating' => 1600,
			'eloReached2400' => FALSE,
			'spcURL' => '',
			'isSoundEffect' => FALSE,
			'isVoice' => FALSE,
			'trackNumber' => 0 // TODO �a serait pas mieux d'aller chercher un num�ro qui existe pas?
		);

		$object = new \stdClass();
		foreach ($data as $key => $value)
			$object->$key = $value;
		return $object;
	}

	public function set_Track($idGame, $title, $length, $composer, $turnedOffByAdmin, $isJingle, $spcURL, $glicko2RD, $glicko2rating, $glicko2sigma, $eloRating, $eloReached2400, $isSoundEffect, $isVoice, $trackNumber) {
		$data = array(
			'idGame' => $idGame,
			'title' => $title,
			'length' => $length,
			'composer' => $composer,
			'turnedOffByAdmin' => $turnedOffByAdmin,
			'isJingle' => $isJingle,
			'glicko2RD' => $glicko2RD,
			'glicko2rating' => $glicko2rating,
			'glicko2sigma' => $glicko2sigma,
			'eloRating' => $eloRating,
			'eloReached2400' => $eloReached2400,
			'spcURL' => $spcURL,
			'isSoundEffect' => $isSoundEffect,
			'isVoice' => $isVoice,
			'trackNumber' => $trackNumber
		);
		$builder = $this->db->table('Track');
		$builder->insert($data);
		return $builder->get();
	}

	public function setTrackIsScreenshotSetFlag($idTrack, $isScreenshotSet) {
		$builder = $this->db->table('Track');
		$builder->where('Track.idTrack', $idTrack);
		return $builder->update(array('isScreenshotSet' => $isScreenshotSet));
	}

	public function delete_Track($idTrack) {
		$builder = $this->db->table('Track');
		return $builder->delete(['idTrack' => $idTrack]);
	}

	public function turnOn_Track($idTrack) {
		$builder = $this->db->table('Track');
		$builder->where('Track.idTrack', $idTrack);
		return $builder->update(['turnedOffByAdmin' => FALSE]);
	}

	public function turnOff_Track($idTrack) {
		$builder = $this->db->table('Track');
		$builder->where('Track.idTrack', $idTrack);
		return $builder->update(['turnedOffByAdmin' => TRUE]);
	}

	public function update_Track($idTrack, $title, $length, $composer, $turnedOffByAdmin, $isJingle, $spcURL, $glicko2RD, $glicko2rating, $glicko2sigma, $eloRating, $eloReached2400, $isSoundEffect, $isVoice, $trackNumber) {
		$builder = $this->db->table('Track');
		$builder->where('Track.idTrack', $idTrack);
		$data = array(
			'title' => $title,
			'length' => $length,
			'composer' => $composer,
			'turnedOffByAdmin' => $turnedOffByAdmin,
			'isJingle' => $isJingle,
			'spcURL' => $spcURL,
			'glicko2RD' => $glicko2RD,
			'glicko2rating' => $glicko2rating,
			'glicko2sigma' => $glicko2sigma,
			'eloRating' => $eloRating,
			'eloReached2400' => $eloReached2400,
			'isSoundEffect' => $isSoundEffect,
			'isVoice' => $isVoice,
			'trackNumber' => $trackNumber
		);
		return $builder->update($data);
	}

	public function get_game_composers($idGame) {
		$builder = $this->db->table('Track');
		$builder->select('composer');
		$builder->distinct();
		$query = $builder->getWhere(['idGame' => $idGame]);

		$retval = array();
		foreach($query->getResult('array') as $result)
			$retval[] = $result['composer'];

		return $retval;
	}

	public function search($searchString) {
		$builder = $this->db->table('Track');
		foreach(explode(' ', $searchString) as $word) {
			$word = preg_quote($word);
			$regex = $this->db->escapeLikeString("({$word})+");
			$builder->where("(title RLIKE '{$regex}')");
		}

		$builder->limit(150);
		$builder->select('Track.*, Game.TitleEng AS gameTitleEng');
		$builder->join('Game', 'Track.idGame = Game.idGame', 'inner');
		$builder->orderBy('Track.title');
		$query = $builder->get();

		$retval = array();
		foreach($query->getResult() as $row) {
			$retval[] = $this->getTrackFromRow($row);
		}
		return $retval;
	}

	public function update_ratings_Track($idTrackWinner, $idTrackLoser) {
		$builderWinner = $this->db->table('Track');
		$builderWinner->where('idTrack', $idTrackWinner);
		$builderLoser = $this->db->table('Track');
		$builderLoser->where('idTrack', $idTrackLoser);
		if($builderWinner->countAllResults(false) == 1 && $builderLoser->countAllResults(false) == 1){
			$queryWinner = $builderWinner->getWhere(['idTrack' => $idTrackWinner]);
			$queryLoser = $builderLoser->getWhere(['idTrack' => $idTrackLoser]);
			
			foreach ($queryWinner->getResult() as $trackWinner) break; //TODO esti que c'est lette
			foreach ($queryLoser->getResult() as $trackLoser) break; //TODO esti que c'est lette
			
			$newGlicko2Winner = $this->glicko2_algo($trackWinner, $trackLoser, TRUE);
			$newGlicko2Loser = $this->glicko2_algo($trackLoser, $trackWinner, FALSE);

			$dataWinner = array(
				'eloRating' => $this->elo_calcul_rating_Winner($idTrackWinner, $trackWinner->eloRating, $idTrackLoser, $trackLoser->eloRating, $trackWinner->eloReached2400),
				'glicko2RD' => $newGlicko2Winner['RD'],
				'glicko2rating' => $newGlicko2Winner['rating'],
				'glicko2sigma' => $newGlicko2Winner['sigma']
			);
			if($dataWinner['eloRating'] >= 2400) $dataWinner['eloReached2400'] = TRUE;
			
			$dataLoser = array(
				'eloRating' => $this->elo_calcul_rating_Loser($idTrackWinner, $trackWinner->eloRating, $idTrackLoser, $trackLoser->eloRating, $trackLoser->eloReached2400),
				'glicko2RD' => $newGlicko2Loser['RD'],
				'glicko2rating' => $newGlicko2Loser['rating'],
				'glicko2sigma' => $newGlicko2Loser['sigma']
			);
			// Pas besoin de checker si le rating d�passe 2400 pour le loser: si oui c'est qu'on l'�tait d�j�
			
			$builderWinner->update($dataWinner, array('idTrack' => $idTrackWinner));
			return $builderLoser->update($dataLoser, array('idTrack' => $idTrackLoser));
		}
	}

	public function getIdTracksForDuel($idUser) {
		// On veut pas que le user vote encore sur des tracks qui �taient de la marde
		$builderShit = $this->db->table('ShitTrack');
		$builderShit->select('idTrack');
		$query = $builderShit->getWhere(['idUser' => $idUser]);
		$idTracksShit = array_map(function($o) { return (int)$o->idTrack; }, $query->getResult());

		$builder = $this->db->table('Track');
		if(count($idTracksShit) > 0)
			$builder->whereNotIn('idTrack', $idTracksShit);

		$builder->limit(2);
		$builder->orderBy('idTrack', 'random');
		$builder->select('idTrack');
		$builder->where('isSoundEffect', 0);
		$builder->where('isJingle', 0);
		$builder->where('isVoice', 0);
		$query = $builder->get();

		return array_map(function($o) { return (int)$o->idTrack; }, $query->getResult());
	}

	public function get_number_of_duels_Track($idTrack) {
		$builder = $this->db->table('DuelResult');
		$builder->where('idTrackWon', $idTrack);
		$builder->orWhere('idTrackLost =', $idTrack);
		return $builder->countAllResults();
	}

	private function getTrackFromRow($row) {
		$row->turnedOffByAdmin = ord($row->turnedOffByAdmin) == 1 || $row->turnedOffByAdmin == 1;
		$row->isJingle = ord($row->isJingle) == 1 || $row->isJingle == 1;
		$row->eloReached2400 = ord($row->eloReached2400) == 1 || $row->eloReached2400 == 1;
		$row->isScreenshotSet = ord($row->isScreenshotSet) == 1 || $row->isScreenshotSet == 1;
		$row->isSoundEffect = ord($row->isSoundEffect) == 1 || $row->isSoundEffect == 1;
		$row->isVoice = ord($row->isVoice) == 1 || $row->isVoice == 1;
		return $row;
	}
	
	private function elo_probability($elo1, $elo2) {
		return (1 / (1 + pow(10, (0-($elo1 - $elo2))/400)));
	}
	
	private function elo_calcul_K($n, $elo, $reached2400) {
		if($n <= 30) return 30;
		if(!$reached2400) return 15;
		return 10;
	}
	
	private function elo_calcul_rating_Winner($idTrackWinner, $eloRatingWinner, $idTrackLoser, $eloRatingLoser, $reached2400Winner) {
		return $eloRatingWinner + $this->elo_calcul_K($this->get_number_of_duels_Track($idTrackWinner), $eloRatingWinner, $reached2400Winner) * (1 - $this->elo_probability($eloRatingWinner, $eloRatingLoser));
	}
	
	private function elo_calcul_rating_Loser($idTrackWinner, $eloRatingWinner, $idTrackLoser, $eloRatingLoser, $reached2400Loser) {
		return $eloRatingLoser + $this->elo_calcul_K($this->get_number_of_duels_Track($idTrackLoser), $eloRatingLoser, $reached2400Loser) * (0 - $this->elo_probability($eloRatingLoser, $eloRatingWinner));
	}
	
	private $glicko2_tau = 0.3;
	private $glicko2_eps = 0.000001;
	
	private function glicko2_convert_rating_to_mu($rating) {
		return ($rating - 1500) / 173.7178;
	}
	
	private function glicko2_convert_RD_to_phi($RD) {
		return $RD / 173.7178;
	}
	
	private function glicko2_convert_mu_to_rating($mu) {
		return $mu * 173.7178 + 1500;
	}
	
	private function glicko2_convert_phi_to_RD($phi) {
		return $phi * 173.7178;
	}
	
	private function glicko2_g($phi) {
		return (1 / sqrt(1 + 3 * $phi / (M_PI * M_PI)));
	}
	
	private function glicko2_s($won) {
		if($won) return 1;
		return 0;
	}
	
	private function glicko2_E($mu, $mu_opponent, $phi_opponent) {
		return 1 / (1 + exp(($this->glicko2_g($phi_opponent)) * ($mu_opponent - $mu)));
	}
	
	private function glicko2_nu($mu, $mu_opponent, $phi_opponent) {
		$g_squared  = $this->glicko2_g($phi_opponent);
		$g_squared *= $g_squared;
		$E = $this->glicko2_E($mu, $mu_opponent, $phi_opponent);
		$un_moins_E = (1 - $E);
		return (1/($g_squared * $E * $un_moins_E));
	}
	
	private function glicko2_delta($nu, $mu, $mu_opponent, $phi_opponent, $won) {
		return $nu * $this->glicko2_g($phi_opponent) * ($this->glicko2_s($won) - $this->glicko2_E($mu, $mu_opponent, $phi_opponent));
	}
	
	private function glicko2_a($sigma) {
		return 2 * log($sigma);
	}
	
	private function glicko2_f($x, $delta, $phi, $nu, $sigma) {
		$phi_squared = $phi * $phi;
		$denom = $phi_squared + $nu + exp($x);
		return ( (exp($x) * ($delta * $delta - $phi_squared - $nu - exp($x))) / (2 * $denom * $denom) - ($x - $this->glicko2_a($sigma)) / ($this->glicko2_tau * $this->glicko2_tau) );
	}
	
	private function glicko2_algo($track, $opponent, $duelResults) {
		// $duelResults: TRUE si gagn�, FALSE si perdu
		$phi = $this->glicko2_convert_RD_to_phi($track->glicko2RD);
		$mu = $this->glicko2_convert_rating_to_mu($track->glicko2rating);
		$sigma = $track->glicko2sigma;
		$phi_opponent = $this->glicko2_convert_RD_to_phi($opponent->glicko2RD);
		$mu_opponent = $this->glicko2_convert_rating_to_mu($opponent->glicko2rating);
		$nu = $this->glicko2_nu($mu, $mu_opponent, $phi_opponent);
		$delta = $this->glicko2_delta($nu, $mu, $mu_opponent, $phi_opponent, $duelResults);
		
		$A = $this->glicko2_a($sigma);
		$delta_squared = $delta * $delta;
		$phi_squared = $phi * $phi;
		if($delta_squared > $phi_squared + $nu) $B = log($delta_squared - $phi_squared - $nu);
		else {
			$k = 1;
			while($this->glicko2_f($A - $k * $this->glicko2_tau, $delta, $phi, $nu, $sigma) < 0) $k++;
			$B = $A - $k * $this->glicko2_tau;
		}
		
		$f_A = $this->glicko2_f($A, $delta, $phi, $nu, $sigma);
		$f_B = $this->glicko2_f($B, $delta, $phi, $nu, $sigma);
		
		while(abs($B-$A) > $this->glicko2_eps) {
			$C = $A + ($A - $B) * $f_A / ($f_B - $f_A);
			$f_C = $this->glicko2_f($C, $delta, $phi, $nu, $sigma);
			
			if($f_C * $f_B < 0) {
				$A = $B;
				$f_A = $f_B;
			}
			else $f_A = $f_A / 2;
			
			$B = $C;
			$f_B = $f_C;
		}
		
		$sigma_prime = exp($A/2);
		$phi_star_squared = $phi_squared + $sigma_prime * $sigma_prime;
		$phi_prime = 1 / sqrt(1 / ($phi_star_squared) + 1 / $nu);
		$mu_prime = $mu + $phi_prime * $phi_prime * $this->glicko2_g($phi_opponent) * ($this->glicko2_s($duelResults) - $this->glicko2_E($mu, $mu_opponent, $phi_opponent));
		
		$new_Rating = $this->glicko2_convert_mu_to_rating($mu_prime);
		$new_RD = $this->glicko2_convert_phi_to_RD($phi_prime);
		
		return array('rating' => $new_Rating, 'RD' => $new_RD, 'sigma' => $sigma_prime);
	}
}