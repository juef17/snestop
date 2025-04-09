<?php

namespace App\Controllers;

use App\Models\News_model;
helper('utility_helper');

/*
class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
}*/

class Home extends Public_controller
{

	public function index()
	{
		$news_model = new News_model();

		$data = $this->getUserViewData();
		$data['news'] = $news_model->get_news();
		$data['view'] = 'home.php';
		$data['page_title'] = 'Top SNES tracks project';
		$data['page_description'] = 'Welcome to the top SNES tracks project!';

		echo view('template.php', $data);
	}

}
