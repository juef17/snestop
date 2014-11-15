<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Screenshot_Request_Dashboard extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Track_Screenshot_Request_model','',TRUE);
		$this->load->model('Game_Screenshot_Request_model','',TRUE);
		$this->load->model('Game_model','',TRUE);
		$this->load->model('Track_model','',TRUE);
	}

	public function index()
	{
		$data = $this->getUserViewData();
		$data['trackRequests'] = $this->Track_Screenshot_Request_model->get_Track_Screenshot_request();
		$data['gameRequests'] = $this->Game_Screenshot_Request_model->get_Game_Screenshot_request();
		$data['view'] = 'screenshot_request_dashboard.php';
		$this->load->view('template.php', $data);
	}

	public function deleteTrack() {
		if(($idUser = $this->input->post('idUser', TRUE)) && ($idTrack = $this->input->post('idTrack', TRUE)))
			$this->Track_Screenshot_Request_model->delete_Track_Screenshot_request($idTrack, $idUser);
		
		redirect('/screenshot_request_dashboard');
	}

	public function deleteGame() {
		if(($idUser = $this->input->post('idUser', TRUE)) && ($idGame = $this->input->post('idGame', TRUE)))
			$this->Game_Screenshot_Request_model->delete_Game_Screenshot_request($idGame, $idUser);
		
		redirect('/screenshot_request_dashboard');
	}

	public function uploadGameScreenshot() {
		if(($idGame = $this->input->post('idGame', TRUE)) && ($game = $this->Game_model->get_Game($idGame))) {
			//if($game->isScreenshotSet) {
				//delete old screenshot
			//}

			//convert uploaded file
			//rename and move it proper.
			redirect("/game/$idGame");
		} else {
			$data = $this->getUserViewData();
			$data['view'] = 'message.php';
			$data['message'] = 'Game not found!';
			$this->load->view('template.php', $data);
		}
	}

	public function uploadTrackScreenshot() {
		if(($idTrack = $this->input->post('idTrack', TRUE)) && ($track = $this->Track_model->get_Track($idTrack))) {
			//if($game->isScreenshotSet) {
				//delete old screenshot
			//}

			//convert uploaded file
			//rename and move it proper.
			redirect("/game/$track->idGame");
		} else {
			$data = $this->getUserViewData();
			$data['view'] = 'message.php';
			$data['message'] = 'Track not found!';
			$this->load->view('template.php', $data);
		}
	}
}
