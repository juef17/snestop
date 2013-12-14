<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Public_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function login() {
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
		
		if($this->form_validation->run()) {
			if ($this->input->post('rememberme'))
				$this->setCookie();
		} else {
			session_unset();
		}
		
		$_SESSION['loginError'] = validation_errors();
		redirect('/home');
	}

	private function setCookie($token) {
		$token = generate_random_string();
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
		$username = $this->input->post('username');

		$user = $this->User_model->login($username, $password);
		if(!$user) {
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return FALSE;
		} else if(!$user->enabled) {
			$this->form_validation->set_message('check_database', 'Sorry, your account has been disabled.');
			return FALSE;
		} else if($user->registrationToken != NULL) {
			$this->form_validation->set_message('check_database', 'You must confirm your<br />email address first!');
			return FALSE;
		} else {
			$_SESSION['loggedUser'] = $user;
			return TRUE;
		}
	}
}
?>
