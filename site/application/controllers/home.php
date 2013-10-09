<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index()
	{
		$this->load->view('templates/header.php');
		$this->load->view('home');
		$this->load->view('templates/footer.php');
	}
}
