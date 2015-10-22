<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Mistake extends Secure_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Mistake_Request_model','',TRUE);
		$this->load->model('Game_model','',TRUE);
	}

	public function index() {
		$data = $this->getUserViewData();
		if($idGame = $this->input->get('game')) {
			$game = $this->Game_model->get_Game($idGame);
			$data['message'] = "Hi! About the game named '{$game->titleEng}', ";
		} else {
			$data['message'] = '';
		}
		$data['view'] = 'request_mistake.php';
		$this->load->view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		
		$this->setValidationRules();
		if($this->form_validation->run()) {
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			$data['message'] = 'Thank you for your report!';
		} else {
			$data['view'] = 'request_mistake.php';
		}

		$this->load->view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('text', 'Text', 'trim|required|xss_clean|max_length[255]');
	}

	private function saveRequest() {
		$idUser = $_SESSION['loggedUser']->idUser;
		$text = $this->input->post('text', TRUE);
		$this->Mistake_Request_model->set_Mistake_request($idUser, $text);
	}
}
