<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Track_model;
use App\Models\Game_model;

helper('utility_helper');

class Edit_Track extends Admin_controller {

	public function index($idTrack) {
		$track_model = new Track_model();
		$game_model = new Game_model();
		$data = $this->getUserViewData();
		$data['track'] = $track_model->get_Track($idTrack);
		$data['gameTitle'] = $game_model->get_Game($data['track']->idGame)->titleEng;
		$data['view'] = 'edit_track.php';
		echo view('template.php', $data);
	}

	public function create($idGame) {
		$track_model = new Track_model();
		$game_model = new Game_model();
		$data = $this->getUserViewData();
		$data['track'] = $track_model->get_new_Track($idGame);
		$data['gameTitle'] = $game_model->get_Game($data['track']->idGame)->titleEng;
		$data['view'] = 'edit_track.php';
		echo view('template.php', $data);
	}

	public function submit() {
		$track_model = new Track_model();
		$game_model = new Game_model();
		$id = intval($this->request->getVar('id'));
		$idGame = $this->request->getVar('idGame');
		//$this->setValidationRules();
		if(true/*$this->form_validation->run()*/) {
			// getVar retourne null si le checkbox était unchecked, d'où les !!...
			$title = $this->request->getVar('title');
			$length = $this->request->getVar('length');
			$composer = $this->request->getVar('composer');
			$turnedOffByAdmin = ! $this->request->getVar('active');
			$isJingle = !!$this->request->getVar('isJingle');
			$glicko2RD = $this->request->getVar('glicko2RD');
			$glicko2rating = $this->request->getVar('glicko2rating');
			$glicko2sigma = $this->request->getVar('glicko2sigma');
			$eloRating = $this->request->getVar('eloRating');
			$spcURL = $this->request->getVar('spcURL');
			$eloReached2400 = !!$this->request->getVar('eloReached2400');
			$isVoice = !!$this->request->getVar('isVoice');
			$isSoundEffect = !!$this->request->getVar('isSoundEffect');
			$trackNumber = $this->request->getVar('trackNumber');
			
			if($id == 0)
				$track_model->set_Track($idGame, $title, $length, $composer, $turnedOffByAdmin, $isJingle, $spcURL, $glicko2RD, $glicko2rating, $glicko2sigma, $eloRating, $eloReached2400, $isSoundEffect, $isVoice, $trackNumber);
			else
				$track_model->update_Track($id, $title, $length, $composer, $turnedOffByAdmin, $isJingle, $spcURL, $glicko2RD, $glicko2rating, $glicko2sigma, $eloRating, $eloReached2400, $isSoundEffect, $isVoice, $trackNumber);
				
			return redirect()->to(base_url() . "/index.php/tracks_dashboard/index/{$idGame}");
		} else {
			$data = $this->getUserViewData();
			if($id == 0)
				$data['track'] = $track_model->get_new_Track($idGame);
			else
				$data['track'] = $track_model->get_Track($id);
			$data['gameTitle'] = $game_model->get_Game($data['track']->idGame)->titleEng;
			$data['view'] = 'edit_track.php';
			echo view('template.php', $data);
		}
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('length', 'Length', 'trim|required|xss_clean');
		$this->form_validation->set_rules('composer', 'Composer', 'trim|required|xss_clean');
		$this->form_validation->set_rules('spcURL', 'SPC URL', 'trim|required|xss_clean');
	}
}
?>
