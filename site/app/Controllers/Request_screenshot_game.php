<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Game_model;
use App\Models\Game_screenshot_request_model;

helper('utility_helper');

class Request_screenshot_game extends Secure_controller {
	
	public function index($idGame) {
		$game_model = new Game_model();
		$data = $this->getUserViewData();
		$data['game'] = $game_model->get_Game($idGame);
		$data['view'] = 'request_screenshot_game.php';
		echo view('template.php', $data);
	}

	public function submit() {
		$game_model = new Game_model();
		$game_screenshot_request_model = new Game_screenshot_request_model();
		$data = $this->getUserViewData();
		$idUser = $_SESSION['loggedUser']->idUser;
		$game = $this->request->getVar('idgame', FILTER_SANITIZE_NUMBER_INT);
		
		// TODO
		//$this->setValidationRules();
		if(true/*$this->form_validation->run()*/) {
			$existaitDeja = !!($game_screenshot_request_model->get_Game_Screenshot_request($game, $idUser));
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			if($existaitDeja)
				$data['message'] = 'Thank you for your submission! It appears you had already submitted a screenshot. Your previous submission has been overwritten by the current one; it will be reviewed and added to the site soon.';
			else
				$data['message'] = 'Thank you for your submission! It will be reviewed and added to the site soon.';
		} else {
			$data['game'] = $game_model->get_Game($this->request->getVar('idgame', FILTER_SANITIZE_NUMBER_INT));
			$data['view'] = 'request_screenshot_game.php';
		}
		echo view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('idgame', 'Game', 'trim|required|xss_clean');
		$this->form_validation->set_rules('screenshotUrl', 'Link to screenshot', 'trim|required|xss_clean');
	}

	private function saveRequest() {
		$game_screenshot_request_model = new Game_screenshot_request_model();
		$idUser = $_SESSION['loggedUser']->idUser;
		$game = $this->request->getVar('idgame', FILTER_SANITIZE_NUMBER_INT);
		$screenshotUrl = $this->request->getVar('screenshotUrl', FILTER_SANITIZE_URL);
		$game_screenshot_request_model->set_Game_Screenshot_request($game, $idUser, $screenshotUrl);
	}
}
