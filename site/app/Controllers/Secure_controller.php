<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class Secure_controller extends BaseController {
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);
		if (! $this->isUserLogged()) {
			require_once(views_dir() . 'session_timeout.php');
			die();
		}
	}
}