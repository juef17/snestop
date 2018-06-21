<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Duelz_History extends Secure_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Duel_Result_model','',TRUE);
	}

	public function index()
	{
		$data = $this->getUserViewData();
		$data['duelResults'] = $this->Duel_Result_model->get_Duel_Result_for_User($_SESSION['loggedUser']->idUser);
		$data['view'] = 'duelz_history.php';
		$this->load->view('template.php', $data);
	}
}
