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
		$data['game'] = $this->Game_model->get_Game($id);
		$data['idTrack'] = $idTrack; //open this track dialog
		$data['composers'] = $this->Track_model->get_game_composers($id);
		$data['tracks'] = $this->Track_model->get_Tracks_for_Game($id);
		$data['view'] = 'game.php';
		$this->load->view('template.php', $data);
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
