<?php
class Track_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Track($idTrack) {
		$query = $this->db->get_where('Track', array('idTrack' => $idTrack));
		return $this->getTrackFromRow($query->row());
	}

	public function get_Tracks_for_Game($idGame) {
		$query = $this->db->get_where('Track', array('idGame' => $idGame));

		$retval = array();
		foreach($query->result() as $row) {
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
			'fadeLength' => 0,
			'composer' => '',
			'turnedOffByAdmin' => FALSE,
			'screenshotURL' => '',
			'isJingle' => FALSE,
			'glicko2RD' => 350,
			'glicko2rating' => 1500,
			'glicko2sigma' => 0.06,
			'eloRating' => 1600,
			'eloReached2400' => FALSE,
			'spcURL' => '',
			'spcEncodedURL' => ''
		);

		$object = new stdClass();
		foreach ($data as $key => $value)
			$object->$key = $value;
		return $object;
	}

	public function set_Track($idGame, $title, $length, $fadeLength, $composer, $turnedOffByAdmin, $screenshotURL, $isJingle, $spcURL, $spcEncodedURL) {
		$data = array(
			'idGame' => $idGame,
			'title' => $title,
			'length' => $length,
			'fadeLength' => $fadeLength,
			'composer' => $composer,
			'turnedOffByAdmin' => $turnedOffByAdmin,
			'screenshotURL' => $screenshotURL,
			'isJingle' => $isJingle,
			'glicko2RD' => 350,
			'glicko2rating' => 1500,
			'glicko2sigma' => 0.06,
			'eloRating' => 1600,
			'eloReached2400' => FALSE,
			'spcURL' => $spcURL,
			'spcEncodedURL' => $spcEncodedURL
		);
		return $this->db->insert('Track', $data);
	}

	public function delete_Track($idTrack) {
		return $this->db->delete('Track', array('idTrack' => $idTrack));
	}

	public function turnOn_Track($idTrack) {
		$this->db->where('Track.idTrack', $idTrack);
		return $this->db->update('Track', array('turnedOffByAdmin' => FALSE));
	}

	public function turnOff_Track($idTrack) {
		$this->db->where('Track.idTrack', $idTrack);
		return $this->db->update('Track', array('turnedOffByAdmin' => TRUE));
	}
	
	public function update_Track($idTrack, $title, $length, $fadeLength, $composer, $turnedOffByAdmin, $screenshotURL, $isJingle, $spcURL, $spcEncodedURL) {
		$this->db->where('Track.idTrack', $idTrack);
		$data = array(
			'title' => $title,
			'length' => $length,
			'fadeLength' => $fadeLength,
			'composer' => $composer,
			'turnedOffByAdmin' => $turnedOffByAdmin,
			'screenshotURL' => $screenshotURL,
			'isJingle' => $isJingle,
			'spcURL' => $spcURL,
			'spcEncodedURL' => $spcEncodedURL
		);
		return $this->db->update('Track', $data);
	}

	public function update_ratings_Track($idTrackWinner, $idTrackLoser) {
		$queryWinner = $this->db->get_where('Track', array('idTrack' => $idTrackWinner));
		$queryLoser = $this->db->get_where('Track', array('idTrack' => $idTrackLoser));
		if($queryWinner->num_rows() == 1 && $queryLoser->num_rows() == 1) {
			$trackWinner = $queryWinner->row();
			$trackLoser = $queryLoser->row();
			
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
			// Pas besoin de checker si le rating dépasse 2400 pour le loser: si oui c'est qu'on l'était déjà
			
			$this->db->update('Track', $dataWinner, array('idTrack' => $idTrackWinner));
			return $this->db->update('Track', $dataLoser, array('idTrack' => $idTrackLoser));
		}
	}

	private function getTrackFromRow($row) {
		$row->turnedOffByAdmin = ord($row->turnedOffByAdmin) == 1 || $row->turnedOffByAdmin == 1;
		$row->isJingle = ord($row->isJingle) == 1 || $row->isJingle == 1;
		$row->eloReached2400 = ord($row->eloReached2400) == 1 || $row->eloReached2400 == 1;
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
	
	public function get_number_of_duels_Track($idTrack) {
		$this->db->where('idTrackWon', $idTrack);
		$this->db->or_where('idTrackLost =', $idTrack); 
		$this->db->from('DuelResult');
		return $this->db->count_all_results();
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
		// $duelResults: TRUE si gagné, FALSE si perdu
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
