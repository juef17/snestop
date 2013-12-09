<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Track_Request_Dashboard extends Secure_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Track_Request_model','',TRUE);
	}

	public function index()
	{
		$data = $this->getUserViewData();
		$data['trackRequests'] = $this->Track_Request_model->get_track_request();
		$data['view'] = 'track_request_dashboard.php';
		$this->load->view('template.php', $data);
	}

	public function delete() {
		if($id = $this->input->post('id', TRUE))
			$this->Track_Request_model->delete_track_request($id);

		redirect('/track_request_dashboard');
	}
}
