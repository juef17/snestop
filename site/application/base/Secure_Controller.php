<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Secure_Controller extends Base_Controller {
	public function __construct() {
		parent::__construct();
		if (! $this->isUserLogged()) {
			require_once(views_dir() . 'session_timeout.php');
			die();
		}
	}
}
