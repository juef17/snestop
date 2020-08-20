<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Community_request_model;

helper('utility_helper');

class Request_community extends Secure_controller {
	
	public function index() {
		$data = $this->getUserViewData();
		$data['view'] = 'request_community.php';
		echo view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		
		$this->setValidationRules();
		// TODO bâtard de forms validations cossins qui ont changé
		if(true/*$this->form_validation->run()*/) {
			$this->saveRequest();

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'Thank you!';
			$data['message'] = 'Thank you for your request!';
		} else {
			$data['view'] = 'request_community.php';
		}

		// TODO c'était tu commenté avant ça ou c'est moi quand on a passé à CI4?
		//$data['requestTrackErrors'] = validation_errors();
		echo view('template.php', $data);
	}

	private function setValidationRules() {
		// TODO bâtard de forms validations cossins qui ont changé
		/*$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('url', 'URL', 'trim|required|xss_clean');*/
	}

	private function saveRequest() {
		$community_request_model = new Community_request_model();
		$idUser = $_SESSION['loggedUser']->idUser;
		$name = $this->request->getVar('name', FILTER_SANITIZE_STRING);
		$url = $this->request->getVar('url', FILTER_SANITIZE_URL);
		$community_request_model->set_Community_request($idUser, $url, $name);
	}
}
