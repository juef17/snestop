<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User_model;

class Complete_Registration extends Public_only_controller {

	public function index($token) {
		$user_model = new User_model();
		$data = $this->getUserViewData();
		$data['view'] = 'message.php';
		if($user_model->removeRegistrationToken($token)) {
			$data['messageTitle'] = 'Congratulations!';
			$data['message'] = 'Thank you for registering! You may now log in using the form on top of the page!';
		} else {
			$data['messageTitle'] = 'Email confirmation failed';
			$data['message'] = 'We were unable to confirm the requested account. Sorry... Please contact us.';
		}
		echo view('template.php', $data);
	}
}
