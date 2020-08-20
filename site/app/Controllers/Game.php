<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Track_model;
use App\Models\Game_model;
use App\Models\Review_model;

helper('utility_helper');

class Game extends Public_controller
{
	public function index($id, $idTrack = null) {
		$track_model = new Track_model();
		$game_model = new Game_model();
		$data = $this->getUserViewData();
		$game = $game_model->get_Game($id);
		$data['game'] = $game;
		$data['idTrack'] = $idTrack; //open this track dialog
		$data['composers'] = $track_model->get_game_composers($id);
		$data['view'] = 'game.php';
		$this->setSocialMeta($data, $game, $idTrack);
		
		echo view('template.php', $data);
	}

	function setSocialMeta(&$data, $game, $idTrack) {
		$track_model = new Track_model();
		$track = NULL;
		if($idTrack)
			$track = $track_model->get_Track($idTrack);
		
		if($track) {
			$data['page_description'] = "{$game->titleEng} - {$track->title}";
			if($track->isScreenshotSet)
				$data['page_image'] = asset_url() . "images/screenshots/track/{$track->idTrack}.png";
		} else {
			$data['page_description'] = $game->titleEng;
			if($game->isScreenshotSet)
				$data['page_image'] = asset_url() . "images/screenshots/game/{$game->idGame}.png";
		}
	}

	//Ajax GET partial view
	public function getTracks($idGame, $showNormalTracks, $showJingles, $showSfx, $showVfx) {
		$track_model = new Track_model();
		$game_model = new Game_model();
		$data = $this->getUserViewData();
		$data['game'] = $game_model->get_Game($idGame);
		$data['tracks'] = $track_model->get_Tracks_for_Game($idGame, $showNormalTracks, $showJingles, $showSfx, $showVfx);
		echo view('includes/game_tracks.php', $data);
	}

	//Ajax GET
	public function getReviewsForTrack($idTrack) {
		$review_model = new Review_model();
		$reviews = $review_model->get_Review_for_track($idTrack);
		echo json_encode($reviews);
	}

	//Ajax GET
	public function getTrack($idTrack) {
		$track_model = new Track_model();
		if($track = $track_model->get_Track($idTrack)) {
			$data['success'] = $track;
			$data['message'] = '';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'Can\'t find track, sorry :(';
		}
		
		echo json_encode($data);
	}
}
