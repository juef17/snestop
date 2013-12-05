<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_Dashboard extends Secure_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('News_model','',TRUE);
	}

	public function index()
	{
		$data = $this->getUserViewData();
		$data['news'] = $this->News_model->get_news();
		$data['view'] = 'news_dashboard.php';
		$this->load->view('template.php', $data);
	}

	public function create() {
		$data = $this->getUserViewData();
		$data['newsitem'] = array(
			'title' => '',
			'date' => '',
			'userName' => '',
			'text' => '',
			'idNews' => '0'
		);
		$data['view'] = 'news_details.php';
		$this->load->view('template.php', $data);
	}

	public function edit($id) {
		$data = $this->getUserViewData();
		$data['newsitem'] = $this->News_model->get_news($id)[0];
		$data['view'] = 'news_details.php';
		$this->load->view('template.php', $data);
	}

	public function submit() {
		$title = $this->input->post('title', TRUE);
		$text = $this->input->post('text', TRUE);
		$idUser = $_SESSION['loggedUser']->idUser;
		$id = $this->input->post('idNews', TRUE);
		$this->News_model->set_news($id, $title, $text, $idUser);
		redirect('/news_dashboard');
	}

	public function delete() {
		if($id = $this->input->post('id', TRUE))
			$this->News_model->delete_news($id);

		redirect('/news_dashboard');
	}
}
