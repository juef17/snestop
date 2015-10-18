<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Profile extends Public_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Playlist_model','',TRUE);
		$this->load->model('Review_model','',TRUE);
		$this->load->model('Playlist_Item_model','',TRUE);
	}

	public function index($username) {
		$data = $this->getUserViewData();
		if($data['user'] = $this->User_model->get_user_profile($username)) {
			$data['playlists'] = $this->Playlist_model->get_Playlists_from_User($data['user']->idUser, true);
			$data['reviews'] = $this->Review_model->get_Review_for_user($data['user']->idUser);
		}
		$data['view'] = 'user_profile.php';
		$this->setSocialMeta($data);
		$this->load->view('template.php', $data);
	}

	function setSocialMeta(&$data) {
		if($user = $data['user']) {
			$nbReviews = count($data['reviews']);
			$ndSharedPlaylists = count($data['playlists']);
			$data['page_description'] = "{$user->userName}'s profile: $nbReviews track reviews and $ndSharedPlaylists shared playlists.";
		} else {
			$data['page_description'] = 'User profile not found';
		}
	}

	//Ajax GET partial view
	public function playlistDetails($idPlaylist) {
		$data = $this->getUserViewData();
		
		$playlist = $this->Playlist_model->get_Playlist($idPlaylist);
		if($playlist && $playlist->public)
		{
			$data['tracks'] = $this->Playlist_Item_model->get_PlaylistItems_for_Playlist($idPlaylist);
			$data['playlist'] = $playlist;
			$this->load->view('user_profile_playlist_dialog.php', $data);
		}
	}
}
?>
