<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class Admin_controller extends BaseController {
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);

		if (! $this->isLoggedUserAdmin())
			redirect('/home');
	}
}
