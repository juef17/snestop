<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Screenshot_Game extends Secure_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Game_Screenshot_Request_model','',TRUE);
		$this->load->model('Game_model','',TRUE);
	}
	
	public function index($idGame) {
		$data = $this->getUserViewData();
		$data['game'] = $this->Game_model->get_Game($idGame);
		$data['view'] = 'request_screenshot_game.php';
		$this->load->view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		$idUser = $_SESSION['loggedUser']->idUser;
		$game = $this->input->post('idgame', TRUE);
		
		$this->setValidationRules();
		if($this->form_validation->run()) {
			$existaitDeja = !!($this->Game_Screenshot_Request_model->get_Game_Screenshot_request($game, $idUser));
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			if($existaitDeja)
				$data['message'] = 'Thank you for your submission! It appears you had already submitted a screenshot. Your previous submission has been overwritten by the current one; it will be reviewed and added to the site soon.';
			else
				$data['message'] = 'Thank you for your submission! It will be reviewed and added to the site soon.';
		} else {
			$data['game'] = $this->Game_model->get_Game($this->input->post('idgame', TRUE));
			$data['view'] = 'request_screenshot_game.php';
		}
		$this->load->view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('idgame', 'Game', 'trim|required|xss_clean');
		$this->form_validation->set_rules('screenshotUrl', 'Link to screenshot', 'trim|required|xss_clean');
	}

	private function saveRequest() {
		$idUser = $_SESSION['loggedUser']->idUser;
		$game = $this->input->post('idgame', TRUE);
		$screenshotUrl = $this->input->post('screenshotUrl', TRUE);
		$this->Game_Screenshot_Request_model->set_Game_Screenshot_request($game, $idUser, $screenshotUrl);
	}
}
