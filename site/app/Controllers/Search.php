<?php namespace App\Controllers;

use App\Models\Game_model;
use App\Models\User_model;
use App\Models\Track_model;

helper('utility_helper');

class Search extends BaseController
{
	public function browse($target, $page = 'A') {
		$data = $this->getUserViewData();
		$target = $this->request->getVar('target') ?? '0';
		
		if($target == '0') {
			$game_model = new Game_model();
			$data['games'] = $game_model->get_Games($page);
			$data['currentPage'] = $page;
			$data['view'] = 'search_results_game.php';
		}
		
		echo view('template.php', $data);
	}

	//POST
	public function index() {
		$data = $this->getUserViewData();
		$target = $this->request->getVar('target') ?? '0';

		$data['searchString'] = $this->request->getVar('searchString');
		if($target == '0') {
			$game_model = new Game_model();
			$data['games'] = $game_model->search($data['searchString']);
			$data['view'] = 'search_results_game.php';
		} elseif($target == '1') {
			$track_model = new Track_model();
			$data['tracks'] = $track_model->search($data['searchString']);
			$data['view'] = 'search_results_track.php';
		} elseif($target == '2') {
			$user_model = new User_model();
			$data['users'] = $user_model->search($data['searchString']);
			$data['view'] = 'search_results_user.php';
		}
		
		echo view('template.php', $data);
	}
}
