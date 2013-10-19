<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index()
	{
		$data['view'] = 'home.php';
		$this->load->view('template.php', $data);
	}
}
