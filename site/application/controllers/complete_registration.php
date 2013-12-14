<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Complete_Registration extends Public_Only_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index($token) {
		$data = $this->getUserViewData();
		$data['view'] = 'message.php';
		if($this->User_model->removeRegistrationToken($token)) {
			$data['messageTitle'] = 'Congratulations!';
			$data['message'] = 'Thank you for registering! You may now log in using the form on top of the page!';
		} else {
			$data['messageTitle'] = 'Email confirmation failed';
			$data['message'] = 'We were unable to confirm the requested account. Sorry... Please contact us.';
		}
		$this->load->view('template.php', $data);
	}
}
?>
