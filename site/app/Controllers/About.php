<?php namespace App\Controllers;

helper('utility_helper');

class About extends BaseController
{
	public function index()
	{
		$data = $this->getUserViewData();
		$data['view'] = 'about.php';
		
		echo view('template.php', $data);
	}
}
