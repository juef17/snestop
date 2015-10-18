<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends Public_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Game_model','',TRUE);
		$this->load->model('Track_model','',TRUE);
		$this->load->model('Review_model','',TRUE);
	}

	public function index($id, $idTrack = null) {
		$data = $this->getUserViewData();
		$game = $this->Game_model->get_Game($id);
		$data['game'] = $game;
		$data['idTrack'] = $idTrack; //open this track dialog
		$data['composers'] = $this->Track_model->get_game_composers($id);
		$data['tracks'] = $this->Track_model->get_Tracks_for_Game($id);
		$data['view'] = 'game.php';
		$this->setSocialMeta($data, $game, $idTrack);
		
		$this->load->view('template.php', $data);
	}

	function setSocialMeta(&$data, $game, $idTrack) {
		$track = NULL;
		if($idTrack) {
			foreach($data['tracks'] as $loadedTrack) {
				if($loadedTrack->idTrack == $idTrack) {
					$track = $loadedTrack;
					break;
				}
			}
		}
		
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

	//Ajax GET
	public function getReviewsForTrack($idTrack) {
		$reviews = $this->Review_model->get_Review_for_track($idTrack);
		echo json_encode($reviews);
	}

	//Ajax GET
	public function getTrack($idTrack) {
		if($track = $this->Track_model->get_Track($idTrack)) {
			$data['success'] = $track;
			$data['message'] = '';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'Can\t find track, sorry :(';
		}
		
		echo json_encode($data);
	}
}
?>
