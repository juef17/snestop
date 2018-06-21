<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Public_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('News_model','',TRUE);
	}

	public function index()
	{
		$data = $this->getUserViewData();
		$data['news'] = $this->News_model->get_news();
		$data['view'] = 'home.php';
		$data['page_title'] = 'Top SNES tracks project';
		$data['page_description'] = 'Welcome to the top SNES tracks project!';
		
		
		$this->load->view('template.php', $data);
	}
}
