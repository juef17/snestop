<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Track_model;
use App\Models\Track_screenshot_request_model;

helper('utility_helper');

class Request_screenshot_track extends Secure_controller {
	
	public function index($idTrack) {
		$track_model = new Track_model();
		$data = $this->getUserViewData();
		$data['track'] = $track_model->get_Track($idTrack);
		$data['view'] = 'request_screenshot_track.php';
		echo view('template.php', $data);
	}

	public function submit() {
		$track_model = new Track_model();
		$track_screenshot_request_model = new Track_screenshot_request_model();
		$data = $this->getUserViewData();
		$idUser = $_SESSION['loggedUser']->idUser;
		$track = $this->request->getVar('idtrack', FILTER_SANITIZE_NUMBER_INT);
		
		//$this->setValidationRules();
		if(true/*$this->form_validation->run()*/) {
			$existaitDeja = !!($track_screenshot_request_model->get_Track_Screenshot_request($track, $idUser));
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			if($existaitDeja)
				$data['message'] = 'Thank you for your submission! It appears you had already submitted a screenshot. Your previous submission has been overwritten by the current one; it will be reviewed and added to the site soon.';
			else
				$data['message'] = 'Thank you for your submission! It will be reviewed and added to the site soon.';
		} else {
			$data['track'] = $track_model->get_Track($this->request->getVar('idtrack', FILTER_SANITIZE_NUMBER_INT));
			$data['view'] = 'request_screenshot_track.php';
		}
		echo view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('idtrack', 'Track', 'trim|required|xss_clean');
		$this->form_validation->set_rules('screenshotUrl', 'Link to screenshot', 'trim|required|xss_clean');
	}

	private function saveRequest() {
		$track_screenshot_request_model = new Track_screenshot_request_model();
		$idUser = $_SESSION['loggedUser']->idUser;
		$track = $this->request->getVar('idtrack', FILTER_SANITIZE_NUMBER_INT);
		$screenshotUrl = $this->request->getVar('screenshotUrl', FILTER_SANITIZE_URL);
		$track_screenshot_request_model->set_Track_Screenshot_request($track, $idUser, $screenshotUrl);
	}
}
