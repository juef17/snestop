<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		session_start();
	}

	protected function isLoggedUserAdmin() {
		if(isset($_SESION['loggedUser'])) {
			return $_SESION['loggedUser']->isAdmin;
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
		} else {
			$data['loggedUserUserName'] = NULL;
			$data['loggedUserIsAdmin'] = FALSE;
		}

		if(isset($_SESSION['loginError'])) {
			$data['loginError'] = $_SESSION['loginError'];
			unset($_SESSION['loginError']);
		} else {
			$data['loginError'] = '';
		}

		return $data;
	}
}
