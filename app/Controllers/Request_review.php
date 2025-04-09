<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Review_model;
use App\Models\Track_model;

helper('utility_helper');

class Request_review extends Secure_controller {
	
	public function index($idTrack) {
		$track_model = new Track_model();
		$data = $this->getUserViewData();
		if($data['track'] = $track_model->get_Track($idTrack)) {
			$data['view'] = 'request_review.php';
		} else {
			$data['message'] = 'Track not found';
			$data['view'] = 'message.php';
		}
		
		echo view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		
		// TODO
		//$this->setValidationRules();
		if(true/*$this->form_validation->run()*/) {
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			$data['message'] = 'Thank you for your review! It is now pending approval.';
		} else {
			$data['view'] = 'request_review.php';
		}

		echo view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('text', 'Text', 'trim|required|xss_clean|min_length[50]');
	}

	private function saveRequest() {
		$review_model = new Review_model();
		$idUser = $_SESSION['loggedUser']->idUser;
		$idTrack = $this->request->getVar('track', FILTER_SANITIZE_STRING);
		$text = $this->request->getVar('text', FILTER_SANITIZE_STRING);
		$review_model->set_Review($idUser, $idTrack, $text);
	}
}
