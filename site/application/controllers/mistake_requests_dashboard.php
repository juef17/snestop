<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mistake_Requests_Dashboard extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Mistake_Request_model','',TRUE);
	}

	public function index()
	{
		$data = $this->getUserViewData();
		$data['mistakes'] = $this->Mistake_Request_model->get_Mistake_request();
		$data['view'] = 'mistake_requests_dashboard.php';
		$this->load->view('template.php', $data);
	}

	public function delete() {
		if($id = $this->input->post('id', TRUE))
			$this->Mistake_Request_model->delete_Mistake_request($id);

		redirect('/mistake_requests_dashboard');
	}
}
