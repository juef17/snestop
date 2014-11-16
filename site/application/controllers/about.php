<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends Public_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$data = $this->getUserViewData();
		$data['view'] = 'about.php';
		
		$this->load->view('template.php', $data);
	}
}
