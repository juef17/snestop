<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('User_model','',TRUE);
	}

	function login() {
		//This method will have the credentials validation
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
		
		if($this->form_validation->run() && $this->input->post('rememberme')) {
			$token = $this->generate_random_string();
			$cookie = array(
				'name'   => 'rememberMeSnestopToken',
				'value'  => $token,
				'expire' => '1209600',  // Two weeks
				'domain' => 'snestop.com',
				'path'   => '/'
			);
			$this->input->set_cookie($cookie);
			$this->User_model->set_token($this->input->post('username'), $token);
		}
		$data['view'] = 'home.php';
		$this->load->view('template.php', $data);
	}

	function autologin() {
		$token = $this->input->post('token');

		var_dump($this->User_model->remembered_login($token)); //un result ici...
	}

	function logout() {
		$username = $this->session->userdata('logged_in')['username'];
		$this->User_model->set_token($username, NULL);
		$this->session->set_userdata('logged_in', NULL);
		$data['view'] = 'home.php';
		$this->load->view('template.php', $data);
	}

	function check_database($password) {
		//Field validation succeeded.  Validate against database
		$username = $this->input->post('username');

		//query the database
		$result = $this->User_model->login($username, $password);
		
		if($result) {
			$this->set_session($result[0]->userName);
			return TRUE;
		} else {
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
		}
	}

	function generate_random_string($length = 50) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}

	function set_session($username) {
		$sess_array = array(
			'username' => $username
		);
		$this->session->set_userdata('logged_in', $sess_array);
	}
}
?>
