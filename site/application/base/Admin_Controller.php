<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends Base_Controller {
	public function __construct() {
		parent::__construct();
		if (! $this->isLoggedUserAdmin())
			redirect('/home');
	}
}
