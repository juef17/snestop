<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Screenshot_Request_Dashboard extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Track_Screenshot_Request_model','',TRUE);
		$this->load->model('Game_Screenshot_Request_model','',TRUE);
		$this->load->model('Game_model','',TRUE);
		$this->load->model('Track_model','',TRUE);

		$config['upload_path'] = assets_dir() . 'upload';
		$config['allowed_types'] = 'gif|jpg|png|bmp';
		$config['overwrite']  = true;
		$this->load->library('upload', $config);
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

	//Ajax POST
	public function uploadScreenshot() {
		$id = $this->input->post('id', TRUE);
		$type = intval($this->input->post('type', TRUE));
		$target = $type === 0
			? $this->Game_model->get_Game($id)
			: $this->Track_model->get_Track($id);

		if(!$target) {
			echo json_encode(array('success' => false, 'message' => "Game or track not found! ({$id})"));
			return;
		}
		
		if(!$this->upload->do_upload()) {
			echo json_encode(array('success' => false, 'message' => $this->upload->display_errors('', '')));
			return;
		}
		
		$inputFile = $this->upload->data()['full_path'];
		$folder = $type === 0 ? 'game' : 'track';
		$outputFile = assets_dir() . "images/screenshots/{$folder}/{$id}.png";
		$conversionResult = imagepng(imagecreatefromstring(file_get_contents($inputFile)), $outputFile);
		unlink($inputFile);
		
		if(!$conversionResult) {
			echo json_encode(array('success' => false, 'message' => 'Image conversion to PNG failed.'));
			return;
		}

		if($type === 0)
			$this->Game_model->setGameIsScreenshotSetFlag($id, true);
		else
			$this->Track_model->setTrackIsScreenshotSetFlag($id, true);
		
		echo json_encode(array('success' => true, 'message' => ''));
	}

	public function unsetScreenshot() {
		$id = $this->input->post('id', TRUE);
		$type = intval($this->input->post('type', TRUE));
		$result = $type === 0
			? $this->Game_model->setGameIsScreenshotSetFlag($id, false)
			: $this->Track_model->setTrackIsScreenshotSetFlag($id, false);
		
		if(!$result) {
			echo json_encode(array('success' => false, 'message' => 'Failed.'));
			return;
		}

		$folder = $type === 0 ? 'game' : 'track';
		$file = assets_dir() . "images/screenshots/{$folder}/{$id}.png";
		if(!@unlink($file))
			echo json_encode(array('success' => false, 'message' => 'File deletion failed but screenshot has still been unset.' . $folder));
		else
			echo json_encode(array('success' => true, 'message' => ''));
	}
}
