<?php namespace App\Controllers;

use App\Models\Duel_result_model;

helper('utility_helper');

class Duelz_History extends Secure_controller
{
	public function index()
	{
		$duel_result_model = new Duel_result_model();
		$data = $this->getUserViewData();
		$data['duelResults'] = $duel_result_model->get_Duel_Result_for_User($_SESSION['loggedUser']->idUser);
		$data['view'] = 'duelz_history.php';
		echo view('template.php', $data);
	}
}
