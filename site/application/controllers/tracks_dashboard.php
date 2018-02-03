<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tracks_Dashboard extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Track_model','',TRUE);
		$this->load->model('Game_model','',TRUE);
	}

	public function index($idGame)
	{
		$data = $this->getUserViewData();
		$data['gameTitle'] = $this->Game_model->get_Game($idGame)->titleEng;
		$data['idGame'] = $idGame;
		$data['tracks'] = $this->Track_model->get_Tracks_for_Game($idGame, TRUE, TRUE, TRUE, TRUE);
		$data['view'] = 'tracks_dashboard.php';
		$this->load->view('template.php', $data);
	}

	public function delete($idGame) {
		if($id = $this->input->post('id', TRUE))
			$this->Track_model->delete_Track($id);

		redirect('/tracks_dashboard/index/' . $idGame);
	}
}
