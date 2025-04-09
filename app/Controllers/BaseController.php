<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\User_model;

helper('cookie');

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];
	protected $session;

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);


		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		$this->session = \Config\Services::session();
		if(!$this->isUserLogged())
			$this->tryAutoLogin();
	}

	protected function isLoggedUserAdmin() {
		if(isset($_SESSION['loggedUser'])) {
			return $_SESSION['loggedUser']->isAdmin;
		} else {
			return FALSE;
		}
	}

	protected function isUserLogged() {
		return isset($_SESSION['loggedUser']);
	}

	protected function getUserViewData() {
		$data = array();

		$data['loggedUser'] = isset($_SESSION['loggedUser'])
			? $_SESSION['loggedUser']
			: null;

		if(isset($_SESSION['loginError'])) {
			$data['loginError'] = $_SESSION['loginError'];
			unset($_SESSION['loginError']);
		} else {
			$data['loginError'] = '';
		}

		$data['page_title'] = 'Top SNES tracks project';
		$data['page_description'] = '';
		$data['page_image'] =  base_url() . '/assets/images/logo.png';
		$data['winape_enabled'] = true;

		return $data;
	}

	private function tryAutoLogin() {
		unset($_SESSION['loggedUser']);
		$token = get_cookie('rememberMeSnestopToken');
		if($token) {
			$user = $this->User_model->remembered_login($token);
			if($user) {
				$_SESSION['loggedUser'] = $user;
			}
		}
	}
}
