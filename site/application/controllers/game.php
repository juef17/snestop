<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends Public_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Game_model','',TRUE);
		$this->load->model('Track_model','',TRUE);
	}

	public function index($id) {
		$data = $this->getUserViewData();
		$data['game'] = $this->Game_model->get_Game($id);
		$data['composers'] = $this->Track_model->get_game_composers($id);
		$data['tracks'] = $this->Track_model->get_Tracks_for_Game($id);
		$data['view'] = 'game.php';
		$this->load->view('template.php', $data);
	}
}
?>
