<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class Public_only_controller extends BaseController {
	public function index()
	{
		if (! $this->isUserLogged())
			redirect('/home');
	}
}
