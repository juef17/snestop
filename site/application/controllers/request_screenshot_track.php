<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Screenshot_Track extends Secure_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Track_Screenshot_Request_model','',TRUE);
		$this->load->model('Track_model','',TRUE);
	}
	
	public function index($idTrack) {
		$data = $this->getUserViewData();
		$data['track'] = $this->Track_model->get_Track($idTrack);
		$data['view'] = 'request_screenshot_track.php';
		$this->load->view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		$idUser = $_SESSION['loggedUser']->idUser;
		$track = $this->input->post('idtrack', TRUE);
		
		$this->setValidationRules();
		if($this->form_validation->run()) {
			$existaitDeja = !!($this->Track_Screenshot_Request_model->get_Track_Screenshot_request($track, $idUser));
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			if($existaitDeja)
				$data['message'] = 'Thank you for your submission! It appears you had already submitted a screenshot. Your previous submission has been overwritten by the current one; it will be reviewed and added to the site soon.';
			else
				$data['message'] = 'Thank you for your submission! It will be reviewed and added to the site soon.';
		} else {
			$data['track'] = $this->Track_model->get_Track($this->input->post('idtrack', TRUE));
			$data['view'] = 'request_screenshot_track.php';
		}
		$this->load->view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('idtrack', 'Track', 'trim|required|xss_clean');
		$this->form_validation->set_rules('screenshotUrl', 'Link to screenshot', 'trim|required|xss_clean');
	}

	private function saveRequest() {
		$idUser = $_SESSION['loggedUser']->idUser;
		$track = $this->input->post('idtrack', TRUE);
		$screenshotUrl = $this->input->post('screenshotUrl', TRUE);
		$this->Track_Screenshot_Request_model->set_Track_Screenshot_request($track, $idUser, $screenshotUrl);
	}
}
