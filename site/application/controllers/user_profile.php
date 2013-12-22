<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Profile extends Public_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index($username) {
		$data = $this->getUserViewData();
		$data['user'] = $this->User_model->get_user_profile($username);
		$data['view'] = 'user_profile.php';
		$this->load->view('template.php', $data);
	}
}
?>
