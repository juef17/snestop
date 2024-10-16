<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Mistake_request_model;
use App\Models\Game_model;

helper('utility_helper');

class Request_Mistake extends Secure_controller {

	public function index() {
		$game_model = new Game_model();
		$data = $this->getUserViewData();
		if($idGame = $this->request->getVar('game')) {
			$game = $game_model->get_Game($idGame);
			$data['message'] = "Hi! About the game named '{$game->titleEng}', ";
		} else {
			$data['message'] = '';
		}
		$data['view'] = 'request_mistake.php';
		echo view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		
		// TODO bâtard de forms validations cossins qui ont changé
		//$this->setValidationRules();
		if(true/*$this->form_validation->run()*/) {
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			$data['message'] = 'Thank you for your report!';
		} else {
			$data['view'] = 'request_mistake.php';
		}

		echo view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('text', 'Text', 'trim|required|xss_clean|max_length[255]');
	}

	private function saveRequest() {
		$mistake_request_model = new Mistake_request_model();
		$idUser = $_SESSION['loggedUser']->idUser;
		$text = $this->request->getVar('text', FILTER_SANITIZE_STRING);
		$mistake_request_model->set_Mistake_request($idUser, $text);
	}
}
