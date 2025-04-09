<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Track_screenshot_request_model;
use App\Models\Game_screenshot_request_model;
use App\Models\Game_model;
use App\Models\Track_model;

helper('utility_helper');

class Screenshot_request_Dashboard extends Admin_controller {

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController($request, $response, $logger);
		// TODO cleanup le vieux code d'upload
		/*$config['upload_path'] = assets_dir() . 'upload';
		$config['allowed_types'] = 'gif|jpg|png|bmp';
		$config['overwrite']  = true;
		$this->load->library('upload', $config);*/
	}

	public function index()
	{
		$track_screenshot_request_model = new Track_screenshot_request_model();
		$game_screenshot_request_model = new Game_screenshot_request_model();
		$data = $this->getUserViewData();
		$data['trackRequests'] = $track_screenshot_request_model->get_Track_Screenshot_request();
		$data['gameRequests'] = $game_screenshot_request_model->get_Game_Screenshot_request();
		$data['view'] = 'screenshot_request_dashboard.php';
		echo view('template.php', $data);
	}

	public function deleteTrack() {
		$track_screenshot_request_model = new Track_screenshot_request_model();
		if(($idUser = $this->request->getVar('idUser', FILTER_SANITIZE_NUMBER_INT)) && ($idTrack = $this->request->getVar('idTrack', FILTER_SANITIZE_NUMBER_INT)))
			$track_screenshot_request_model->delete_Track_Screenshot_request($idTrack, $idUser);
		
		return redirect()->to(base_url() . "screenshot_request_dashboard");
	}

	public function deleteGame() {
		$game_screenshot_request_model = new Game_screenshot_request_model();
		if(($idUser = $this->request->getVar('idUser', FILTER_SANITIZE_NUMBER_INT)) && ($idGame = $this->request->getVar('idGame', FILTER_SANITIZE_NUMBER_INT)))
			$game_screenshot_request_model->delete_Game_Screenshot_request($idGame, $idUser);
		
		return redirect()->to(base_url() . "screenshot_request_dashboard");
	}

	//Ajax POST
	// TODO fuck ça j'suis trop lâche pour faire ça à soir
	public function uploadScreenshot() {
		$game_model = new Game_model();
		$track_model = new Track_model();
		$id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT);
		$type = intval($this->request->getVar('type', FILTER_SANITIZE_STRING));
		$target = $type === 0
			? $game_model->get_Game($id)
			: $track_model->get_Track($id);

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
			$game_model->setGameIsScreenshotSetFlag($id, true);
		else
			$track_model->setTrackIsScreenshotSetFlag($id, true);
		
		echo json_encode(array('success' => true, 'message' => ''));
	}

	public function unsetScreenshot() {
		$game_model = new Game_model();
		$track_model = new Track_model();
		$id = $this->request->getVar('id', FILTER_SANITIZE_NUMBER_INT);
		$type = intval($this->request->getVar('type', FILTER_SANITIZE_STRING));
		$result = $type === 0
			? $game_model->setGameIsScreenshotSetFlag($id, false)
			: $track_model->setTrackIsScreenshotSetFlag($id, false);
		
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
