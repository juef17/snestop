<?php namespace App\Controllers;

use App\Models\News_model;

helper('utility_helper');

class News_dashboard extends Admin_controller
{
	public function index()
	{
		$news_model = new News_model();
		$data = $this->getUserViewData();
		$data['news'] = $news_model->get_news();
		$data['view'] = 'news_dashboard.php';
		echo view('template.php', $data);
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
		echo view('template.php', $data);
	}

	public function edit($id) {
		$news_model = new News_model();
		$data = $this->getUserViewData();
		$data['newsitem'] = $news_model->get_news($id)[0];
		$data['view'] = 'news_details.php';
		echo view('template.php', $data);
	}

	public function submit() {
		$news_model = new News_model();
		$title = $this->request->getVar('title', FILTER_SANITIZE_STRING);
		$text = $this->request->getVar('text', FILTER_SANITIZE_STRING);
		$idUser = $_SESSION['loggedUser']->idUser;
		$id = $this->request->getVar('idNews', FILTER_SANITIZE_NUMBER_INT);
		$news_model->set_news($id, $title, $text, $idUser);
		return redirect()->to(base_url() . 'news_dashboard');
	}

	public function delete() {
		$news_model = new News_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$news_model->delete_news($id);

		return redirect()->to(base_url() . 'news_dashboard');
	}
}
