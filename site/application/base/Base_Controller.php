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
		
		if(isset($_SESSION['loggedUser'])) {
			$loggedUser = $_SESSION['loggedUser'];
			$data['loggedUserUserName'] = $loggedUser->userName;
			$data['loggedUserIsAdmin'] = $loggedUser->isAdmin == 1;
			$data['playerModeLoop'] = $loggedUser->loop;
			$data['playerModeRandomize'] = $loggedUser->randomize;
			$data['isUserLogged'] = true;
		} else {
			$data['loggedUserUserName'] = NULL;
			$data['loggedUserIsAdmin'] = FALSE;
			$data['isUserLogged'] = FALSE;
		}

		if(isset($_SESSION['loginError'])) {
			$data['loginError'] = $_SESSION['loginError'];
			unset($_SESSION['loginError']);
		} else {
			$data['loginError'] = '';
		}

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
