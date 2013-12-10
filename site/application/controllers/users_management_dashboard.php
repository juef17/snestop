<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_Management_Dashboard extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('User_model','',TRUE);
	}

	public function index() {
		$data = $this->getUserViewData();
		$data['users'] = $this->User_model->get_users_list();
		$data['view'] = 'users_management_dashboard.php';
		$this->load->view('template.php', $data);
	}

	public function disableUser() {
		if($id = $this->input->post('id', TRUE))
			$this->User_model->setUserEnabledState($id, FALSE);

		redirect('/users_management_dashboard');
	}

	public function enableUser() {
		if($id = $this->input->post('id', TRUE))
			$this->User_model->setUserEnabledState($id, TRUE);

		redirect('/users_management_dashboard');
	}

	public function delete() {
		if($id = $this->input->post('id', TRUE))
			$this->User_model->deleteUser($id);

		redirect('/users_management_dashboard');
	}
}
