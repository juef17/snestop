<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Game_model;

helper('utility_helper');

class Games_Dashboard extends Admin_controller {
	
	public function index($page = 1)
	{
		$game_model = new Game_model();
		$data = $this->getUserViewData();
		$data['games'] = $game_model->get_Games($page);
		$data['nbPages'] = $game_model->get_nb_pages();
		$data['currentPage'] = $page;
		$data['view'] = 'games_dashboard.php';
		echo view('template.php', $data);
	}

	public function add() {
		$game_model = new Game_model();
		$data = $this->getUserViewData();
		//TODO
		//$this->setAddValidationRules();
		if(true/*$this->form_validation->run()*/) {
			$this->saveGame();
			return redirect()->to(base_url() . "games_dashboard"); //Pour pas réafficher les valeurs soumises.
		} else {
			$data['games'] = $game_model->get_Games(1);
			$data['nbPages'] = $game_model->get_nb_pages();
			$data['currentPage'] = 1;
			$data['view'] = 'games_dashboard.php';
			echo view('template.php', $data);
		}
	}

	public function update($page) {
		$game_model = new Game_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT)) {
			//$this->setUpdateValidationRules($id);
			if(true/*$this->form_validation->run()*/) {
				$this->updateGame($id);
				return redirect()->to(base_url() . "games_dashboard/index/$page");
			} else {
				$data = $this->getUserViewData();
				$data['games'] = $game_model->get_Games($page);
				$data['nbPages'] = $game_model->get_nb_pages();
				$data['currentPage'] = $page;
				$data['view'] = 'games_dashboard.php';
				echo view('template.php', $data);
			}
		} else {
			return redirect()->to(base_url() . "games_dashboard/index/$page");
		}
	}

	public function delete($page) {
		$game_model = new Game_model();
		if($id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT))
			$game_model->delete_Game($id);

		return redirect()->to(base_url() . "games_dashboard/index/$page");
	}

	private function setAddValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('game_titleeng', 'English title', 'trim|required|xss_clean|is_unique[Game.titleEng]');
		$this->form_validation->set_rules('game_titlejap', 'Japanese title', 'trim|xss_clean|is_unique[Game.titleJap]');
		$this->form_validation->set_rules('game_rsn', 'RSN File', 'trim|required|xss_clean');
	}

	private function setUpdateValidationRules($id) {
		$game_model = new Game_model();
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		
		$game = $game_model->get_Game($id);
		if($this->request->getVar("game_{$id}_titleeng") != $game->titleEng)
			$this->form_validation->set_rules("game_{$id}_titleeng", 'English title', 'trim|required|xss_clean|is_unique[Game.titleEng]');
		
		if($this->request->getVar("game_{$id}_titlejap") != $game->titleJap)
			$this->form_validation->set_rules("game_{$id}_titlejap", 'Japanese title', 'trim|xss_clean|is_unique[Game.titleJap]');

		$this->form_validation->set_rules("game_{$id}_rsn", 'RSN File', 'trim|required|xss_clean');
	}

	private function saveGame() {
		$game_model = new Game_model();
		$titleEng = $this->request->getVar('game_titleeng', FILTER_SANITIZE_STRING);
		$titleJap = $this->request->getVar('game_titlejap', FILTER_SANITIZE_STRING);
		$rsn = $this->request->getVar('game_rsn', FILTER_SANITIZE_STRING);
		$game_model->set_Game($titleJap, $titleEng, $rsn);
	}

	private function updateGame($id) {
		$game_model = new Game_model();
		$titleeng = $this->request->getVar("game_{$id}_titleeng", FILTER_SANITIZE_STRING);
		$titlejap = $this->request->getVar("game_{$id}_titlejap", FILTER_SANITIZE_STRING);
		$rsn = $this->request->getVar("game_{$id}_rsn", FILTER_SANITIZE_STRING);
		$game_model->update_Game($id, $titlejap, $titleeng, $rsn);
	}
}
