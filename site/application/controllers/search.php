<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Public_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('User_model','',TRUE);
		$this->load->model('Game_model','',TRUE);
		$this->load->model('Track_model','',TRUE);
	}

	public function browse($target, $page = 'A') {
		$data = $this->getUserViewData();
		$target = $this->input->post('target');
		
		if($target == '0') {
			$data['games'] = $this->Game_model->get_Games($page);
			$data['currentPage'] = $page;
			$data['view'] = 'search_results_game.php';
		}
		
		$this->load->view('template.php', $data);
	}

	//POST
	public function index() {
		$data = $this->getUserViewData();
		$target = $this->input->post('target');
		
		$data['searchString'] = $this->input->post('searchString');
		if($target == '0') {
			$data['games'] = $this->Game_model->search($data['searchString']);
			$data['view'] = 'search_results_game.php';
		} elseif($target == '1') {
			$data['tracks'] = $this->Track_model->search($data['searchString']);
			$data['view'] = 'search_results_track.php';
		} elseif($target == '2') {
			$data['users'] = $this->User_model->search($data['searchString']);
			$data['view'] = 'search_results_user.php';
		}
		
		$this->load->view('template.php', $data);
	}
}
?>
