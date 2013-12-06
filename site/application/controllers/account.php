<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Public_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function login() {
		//This method will have the credentials validation
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
		
		if($this->form_validation->run()) {
			if ($this->input->post('rememberme'))
				$this->setCookie();
		} else {
			session_unset();
			$this->form_validation->set_message('check_database', 'Invalid username or password');
		}
		
		$_SESSION['loginError'] = validation_errors();
		redirect('/home');
	}

	private function setCookie($token) {
		$token = $this->generate_random_string();
		$cookie = array(
			'name'   => 'rememberMeSnestopToken',
			'value'  => $token,
			'expire' => '1209600'  // Two weeks
		);
		$this->input->set_cookie($cookie);
		$this->User_model->set_token($this->input->post('username'), $token);
	}

	private function deleteCookie() {
		$cookie = array(
			 'name'=>'rememberMeSnestopToken',
			 'value'=>'',
			 'expire'=>'0'
		 );
		$this->input->set_cookie($cookie);
		$username = $_SESSION['loggedUser']->userName;
		$this->User_model->set_token($username, NULL);
	}

	public function logout() {
		$this->deleteCookie();
		session_unset();
		redirect('/home');
	}

	public function check_database($password) {
		//Field validation succeeded.  Validate against database
		$username = $this->input->post('username');

		//query the database
		$result = $this->User_model->login($username, $password);
		
		if($result) {
			$_SESSION['loggedUser'] = $result;
			return TRUE;
		} else {
			return false;
		}
	}

	private function generate_random_string($length = 50) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
}
?>
