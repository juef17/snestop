<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Mistake_request_model;

helper('utility_helper');

class Mistake_request_model extends Admin_controller {

	public function index()
	{
		$mistake_request_model = new Mistake_request_model();
		$data = $this->getUserViewData();
		$data['mistakes'] = $mistake_request_model->get_Mistake_request();
		$data['view'] = 'mistake_requests_dashboard.php';
		echo view('template.php', $data);
	}

	public function delete() {
		$mistake_request_model = new Mistake_request_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$mistake_request_model->delete_Mistake_request($id);

		return redirect()->to(base_url() . "/mistake_requests_dashboard");
	}
}
