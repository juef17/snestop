<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Track extends Secure_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Track_Request_model','',TRUE);
	}
	
	public function index() {
		$data = $this->getUserViewData();
		$data['view'] = 'request_track.php';
		//$data['requestTrackErrors'] = '';
		$this->load->view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		
		$this->setValidationRules();
		if($this->form_validation->run()) {
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			$data['message'] = 'Thank you for your request!';
		} else {
			$data['view'] = 'request_track.php';
		}

		//$data['requestTrackErrors'] = validation_errors();
		$this->load->view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('game', 'Game', 'trim|required|xss_clean');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('trackUrl', 'Link to track', 'trim|xss_clean');
	}

	private function saveRequest() {
		$idUser = $_SESSION['loggedUser']->idUser;
		$game = $this->input->post('game', TRUE);
		$title = $this->input->post('title', TRUE);
		$trackUrl = $this->input->post('trackUrl', TRUE);
		$this->Track_Request_model->set_track_request($idUser, $game, $title, $trackUrl);
	}
}
