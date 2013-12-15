<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Communities_Dashboard extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Community_model','',TRUE);
	}

	public function index()
	{
		$data = $this->getUserViewData();
		$data['communities'] = $this->Community_model->get_Community();
		$data['view'] = 'communities_dashboard.php';
		$this->load->view('template.php', $data);
	}

	public function delete() {
		if($id = $this->input->post('id', TRUE))
			$this->Community_model->delete_Community($id);

		redirect('/communities_dashboard');
	}

	public function update() {
		if($id = $this->input->post('id', TRUE)) {
			$name = $this->input->post('community_name', TRUE);
			$token = $this->input->post('community_token', TRUE);
			$URL = $this->input->post('community_URL', TRUE);
			$this->Community_model->update_Community($id, $name, $token, $URL);
		}

		redirect('/communities_dashboard');
	}

	public function add() {
		if($name = $this->input->post('community_name', TRUE)) {
			$token = $this->input->post('community_token', TRUE);
			$URL = $this->input->post('community_URL', TRUE);
			$this->Community_model->set_Community($name, $token, $URL);
		}

		redirect('/communities_dashboard');
	}
}
