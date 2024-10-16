<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Track_request_model;

helper('utility_helper');

class Request_track extends Secure_controller {
	
	public function index() {
		$data = $this->getUserViewData();
		$data['view'] = 'request_track.php';
		//$data['requestTrackErrors'] = '';
		echo view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		
		//$this->setValidationRules();
		if(true/*$this->form_validation->run()*/) {
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			$data['message'] = 'Thank you for your request!';
		} else {
			$data['view'] = 'request_track.php';
		}

		//$data['requestTrackErrors'] = validation_errors();
		echo view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('game', 'Game', 'trim|required|xss_clean');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('trackUrl', 'Link to track', 'trim|xss_clean');
	}

	private function saveRequest() {
		$track_request_model = new Track_request_model();
		$idUser = $_SESSION['loggedUser']->idUser;
		$game = $this->request->getVar('game', FILTER_SANITIZE_STRING);
		$title = $this->request->getVar('title', FILTER_SANITIZE_STRING);
		$trackUrl = $this->request->getVar('trackUrl', FILTER_SANITIZE_URL);
		$track_request_model->set_track_request($idUser, $game, $title, $trackUrl);
	}
}
