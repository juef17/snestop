<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Track_request_model;

helper('utility_helper');

class Track_Request_Dashboard extends Admin_controller {

	public function index()
	{
		$track_request_model = new Track_request_model();
		$data = $this->getUserViewData();
		$data['trackRequests'] = $track_request_model->get_track_request();
		$data['view'] = 'track_request_dashboard.php';
		echo view('template.php', $data);
	}

	public function delete() {
		$track_request_model = new Track_request_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$track_request_model->delete_track_request($id);

		return redirect()->to(base_url() . 'track_request_dashboard');
	}
}
