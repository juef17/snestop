<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User_model;

helper('utility_helper');

class Users_management_dashboard extends Admin_controller {

	public function index() {
		$user_model = new User_model();
		$data = $this->getUserViewData();
		$data['users'] = $user_model->get_users_list();
		$data['view'] = 'users_management_dashboard.php';
		echo view('template.php', $data);
	}

	public function disableUser() {
		$user_model = new User_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$user_model->setUserEnabledState($id, FALSE);

		return redirect()->to(base_url() . 'users_management_dashboard');
	}

	public function enableUser() {
		$user_model = new User_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$user_model->setUserEnabledState($id, TRUE);

		return redirect()->to(base_url() . 'users_management_dashboard');
	}

	public function delete() {
		$user_model = new User_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$user_model->deleteUser($id);

		return redirect()->to(base_url() . 'users_management_dashboard');
	}

	public function reset_password() {
		$user_model = new User_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$user_model->resetPassword($id);

		return redirect()->to(base_url() . 'users_management_dashboard');
	}
}
