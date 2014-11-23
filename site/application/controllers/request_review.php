<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Review extends Secure_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Review_model','',TRUE);
		$this->load->model('Track_model','',TRUE);
	}
	
	public function index($idTrack) {
		$data = $this->getUserViewData();
		if($data['track'] = $this->Track_model->get_Track($idTrack)) {
			$data['view'] = 'request_review.php';
		} else {
			$data['message'] = 'Track not found';
			$data['view'] = 'message.php';
		}
		
		$this->load->view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		
		$this->setValidationRules();
		if($this->form_validation->run()) {
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			$data['message'] = 'Thank you for your review! It is now pending approval.';
		} else {
			$data['view'] = 'request_review.php';
		}

		$this->load->view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('text', 'Text', 'trim|required|xss_clean|min_length[50]');
	}

	private function saveRequest() {
		$idUser = $_SESSION['loggedUser']->idUser;
		$idTrack = $this->input->post('track', TRUE);
		$text = $this->input->post('text', TRUE);
		$this->Review_model->set_Review($idUser, $idTrack, $text);
	}
}
