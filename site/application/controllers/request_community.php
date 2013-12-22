<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Community extends Secure_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Community_Request_model','',TRUE);
	}
	
	public function index() {
		$data = $this->getUserViewData();
		$data['view'] = 'request_community.php';
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
			$data['view'] = 'request_community.php';
		}

		//$data['requestTrackErrors'] = validation_errors();
		$this->load->view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('url', 'URL', 'trim|required|xss_clean');
	}

	private function saveRequest() {
		$idUser = $_SESSION['loggedUser']->idUser;
		$name = $this->input->post('name', TRUE);
		$url = $this->input->post('url', TRUE);
		$this->Community_Request_model->set_Community_request($idUser, $url, $name);
	}
}
