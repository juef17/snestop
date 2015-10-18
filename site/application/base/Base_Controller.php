<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('User_model','',TRUE);
		session_start();
		if(!$this->isUserLogged())
			$this->tryAutoLogin();
	}

	protected function isLoggedUserAdmin() {
		if(isset($_SESSION['loggedUser'])) {
			return $_SESSION['loggedUser']->isAdmin;
		} else {
			return FALSE;
		}
	}

	protected function isUserLogged() {
		return isset($_SESSION['loggedUser']);
	}

	protected function getUserViewData() {
		$data = array();
		
		$data['loggedUser'] = isset($_SESSION['loggedUser'])
			? $_SESSION['loggedUser']
			: null;

		if(isset($_SESSION['loginError'])) {
			$data['loginError'] = $_SESSION['loginError'];
			unset($_SESSION['loginError']);
		} else {
			$data['loginError'] = '';
		}

		$data['page_title'] = 'Top SNES tracks project';
		$data['page_description'] = '';
		$data['page_image'] =  base_url() . 'assets/images/logo.png';

		return $data;
	}

	private function tryAutoLogin() {
		unset($_SESSION['loggedUser']);
		$token = $this->input->cookie('rememberMeSnestopToken');
		if($token) {
			$user = $this->User_model->remembered_login($token);
			if($user) {
				$_SESSION['loggedUser'] = $user;
			}
		}
	}
}
