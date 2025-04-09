<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Track_model;
use App\Models\Game_model;

helper('utility_helper');

class Tracks_dashboard extends Admin_controller {

	public function index($idGame)
	{
		$track_model = new Track_model();
		$game_model = new Game_model();
		$data = $this->getUserViewData();
		$data['gameTitle'] = $game_model->get_Game($idGame)->titleEng;
		$data['idGame'] = $idGame;
		$data['tracks'] = $track_model->get_Tracks_for_Game($idGame, TRUE, TRUE, TRUE, TRUE);
		$data['view'] = 'tracks_dashboard.php';
		echo view('template.php', $data);
	}

	public function delete($idGame) {
		$track_model = new Track_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$track_model->delete_Track($id);

		return redirect()->to(base_url() . 'tracks_dashboard/index/' . $idGame);
	}
}
