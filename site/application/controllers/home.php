<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('News_model','',TRUE);
	}

	public function index()
	{
		$data['news'] = $this->News_model->get_news();
		$data['view'] = 'home.php';
		$this->load->view('template.php', $data);
	}

	function logout() { //EUH pu bon j'pense!
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('home', 'refresh');
	}

}
