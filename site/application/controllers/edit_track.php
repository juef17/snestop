<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit_Track extends Admin_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Track_model','',TRUE);
		$this->load->model('Game_model','',TRUE);
	}

	public function index($idTrack) {
		$data = $this->getUserViewData();
		$data['track'] = $this->Track_model->get_Track($idTrack);
		$data['gameTitle'] = $this->Game_model->get_Game($data['track']->idGame)->titleEng;
		$data['view'] = 'edit_track.php';
		$this->load->view('template.php', $data);
	}

	public function create($idGame) {
		$data = $this->getUserViewData();
		$data['track'] = $this->Track_model->get_new_Track($idGame);
		$data['gameTitle'] = $this->Game_model->get_Game($data['track']->idGame)->titleEng;
		$data['view'] = 'edit_track.php';
		$this->load->view('template.php', $data);
	}

	public function submit() {
		//$this->setEditUserData($data);

		$id = intval($this->input->post('id'));
		$idGame = $this->input->post('idGame');
		$this->setValidationRules();
		if($this->form_validation->run()) {
			$title = $this->input->post('title');
			$length = $this->input->post('length');
			$fadeLength = $this->input->post('fadeLength');
			$composer = $this->input->post('composer');
			$turnedOffByAdmin = ! $this->input->post('active');
			$screenshotURL = $this->input->post('screenshotURL');
			$isJingle = $this->input->post('isJingle');
			$glicko2RD = $this->input->post('glicko2RD');
			$glicko2rating = $this->input->post('glicko2rating');
			$glicko2sigma = $this->input->post('glicko2sigma');
			$eloRating = $this->input->post('eloRating');
			$spcURL = $this->input->post('spcURL');
			$spcEncodedURL = $this->input->post('spcEncodedURL');
			$eloReached2400 = $this->input->post('eloReached2400');
			
			if($id == 0)
				$this->Track_model->set_Track($idGame, $title, $length, $fadeLength, $composer, $turnedOffByAdmin, $screenshotURL, $isJingle, $spcURL, $spcEncodedURL);
			else
				$this->Track_model->update_Track($id, $title, $length, $fadeLength, $composer, $turnedOffByAdmin, $screenshotURL, $isJingle, $spcURL, $spcEncodedURL);
				
			redirect("/tracks_dashboard/index/{$idGame}");
		} else {
			$data = $this->getUserViewData();
			if($id == 0)
				$data['track'] = $this->Track_model->get_new_Track($idGame);
			else
				$data['track'] = $this->Track_model->get_Track($id);
			$data['gameTitle'] = $this->Game_model->get_Game($data['track']->idGame)->titleEng;
			$data['view'] = 'edit_track.php';
			$this->load->view('template.php', $data);
		}
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('length', 'Length', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fadeLength', 'Fade length', 'trim|required|xss_clean');
		$this->form_validation->set_rules('composer', 'Composer', 'trim|required|xss_clean');
		$this->form_validation->set_rules('screenshotURL', 'Screenshot URL', 'trim|xss_clean');
		$this->form_validation->set_rules('spcURL', 'SPC URL', 'trim|required|xss_clean');
		$this->form_validation->set_rules('spcEncodedURL', 'SPC encoded URL', 'trim|required|xss_clean');
	}
}
?>
