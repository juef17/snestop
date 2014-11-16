<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Games_Dashboard extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Game_model','',TRUE);
	}

	public function index($page = 1)
	{
		$data = $this->getUserViewData();
		$data['games'] = $this->Game_model->get_Games($page);
		$data['nbPages'] = $this->Game_model->get_nb_pages();
		$data['currentPage'] = $page;
		$data['view'] = 'games_dashboard.php';
		$this->load->view('template.php', $data);
	}

	public function add() {
		$data = $this->getUserViewData();
		$this->setAddValidationRules();
		if($this->form_validation->run()) {
			$this->saveGame();
			redirect('/games_dashboard'); //Pour pas réafficher les valeurs soumises.
		} else {
			$data['games'] = $this->Game_model->get_Games(1);
			$data['nbPages'] = $this->Game_model->get_nb_pages();
			$data['currentPage'] = 1;
			$data['view'] = 'games_dashboard.php';
			$this->load->view('template.php', $data);
		}
	}

	public function update($page) {
		if($id = $this->input->post('id', TRUE)) {
			$this->setUpdateValidationRules($id);
			if($this->form_validation->run()) {
				$this->updateGame($id);
				redirect('/games_dashboard/index/' . $page);
			} else {
				$data = $this->getUserViewData();
				$data['games'] = $this->Game_model->get_Games($page);
				$data['nbPages'] = $this->Game_model->get_nb_pages();
				$data['currentPage'] = $page;
				$data['view'] = 'games_dashboard.php';
				$this->load->view('template.php', $data);
			}
		} else {
			redirect('/games_dashboard/index/' . $page);
		}
	}

	public function delete($page) {
		if($id = $this->input->post('id', TRUE))
			$this->Game_model->delete_Game($id);

		redirect('/games_dashboard/index/' . $page);
	}

	private function setAddValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('game_titleeng', 'English title', 'trim|required|xss_clean|is_unique[Game.titleEng]');
		$this->form_validation->set_rules('game_titlejap', 'Japanese title', 'trim|xss_clean|is_unique[Game.titleJap]');
		$this->form_validation->set_rules('game_rsn', 'RSN File', 'trim|required|xss_clean');
	}

	private function setUpdateValidationRules($id) {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		
		$game = $this->Game_model->get_Game($id);
		if($this->input->post("game_{$id}_titleeng") != $game->titleEng)
			$this->form_validation->set_rules("game_{$id}_titleeng", 'English title', 'trim|required|xss_clean|is_unique[Game.titleEng]');
		
		if($this->input->post("game_{$id}_titlejap") != $game->titleJap)
			$this->form_validation->set_rules("game_{$id}_titlejap", 'Japanese title', 'trim|xss_clean|is_unique[Game.titleJap]');

		$this->form_validation->set_rules("game_{$id}_rsn", 'RSN File', 'trim|required|xss_clean');
	}

	private function saveGame() {
		$titleEng = $this->input->post('game_titleeng', TRUE);
		$titleJap = $this->input->post('game_titlejap', TRUE);
		$rsn = $this->input->post('game_rsn', TRUE);
		$this->Game_model->set_Game($titleJap, $titleEng, $rsn);
	}

	private function updateGame($id) {
		$titleeng = $this->input->post("game_{$id}_titleeng", TRUE);
		$titlejap = $this->input->post("game_{$id}_titlejap", TRUE);
		$rsn = $this->input->post("game_{$id}_rsn", TRUE);
		$this->Game_model->update_Game($id, $titlejap, $titleeng, $rsn);
	}
}
