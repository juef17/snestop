<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Community_model;
use App\Models\Community_request_model;

helper('utility_helper');

class Communities_dashboard extends Admin_controller {

	public function index()
	{
		$community_model = new Community_model();
		$community_request_model = new Community_request_model();
		$data = $this->getUserViewData();
		$data['communities'] = $community_model->get_Community();
		$data['requests'] = $community_request_model->get_Community_requests_for_dashboard();
		$data['view'] = 'communities_dashboard.php';
		echo view('template.php', $data);
	}

	public function delete() {
		$community_model = new Community_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$community_model->delete_Community($id);

		return redirect()->to(base_url() . "/index.php/communities_dashboard");
	}

	public function deleteRequest() {
		$community_request_model = new Community_request_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$community_request_model->delete_Community_request($id);

		return redirect()->to(base_url() . "/index.php/communities_dashboard");
	}

	public function update() {
		$community_model = new Community_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT)) {
			$name = $this->request->getVar('community_name', FILTER_SANITIZE_STRING);
			$token = $this->request->getVar('community_token', FILTER_SANITIZE_STRING);
			$URL = $this->request->getVar('community_URL', FILTER_SANITIZE_URL);
			$community_model->update_Community($id, $name, $token, $URL);
		}

		return redirect()->to(base_url() . "/index.php/communities_dashboard");
	}

	public function add() {
		$community_model = new Community_model();
		if($name = $this->request->getVar('community_name', FILTER_SANITIZE_STRING)) {
			$token = $this->request->getVar('community_token', FILTER_SANITIZE_STRING);
			$URL = $this->request->getVar('community_URL', FILTER_SANITIZE_URL);
			$community_model->set_Community($name, $token, $URL);
		}

		return redirect()->to(base_url() . "/index.php/communities_dashboard");
	}
}
