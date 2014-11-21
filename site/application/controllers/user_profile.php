<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Profile extends Public_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Playlist_model','',TRUE);
	}

	public function index($username) {
		$data = $this->getUserViewData();
		$data['user'] = $this->User_model->get_user_profile($username);
		$data['playlists'] = $this->Playlist_model->get_Playlists_from_User($data['user']->idUser, true);
		$data['view'] = 'user_profile.php';
		$this->load->view('template.php', $data);
	}
}
?>
