<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Playlist_model;
use App\Models\Review_model;
use App\Models\User_model;
use App\Models\Playlist_item_model;
use App\Models\Duel_result_model;

helper('utility_helper');

class User_profile extends Public_controller {
	
	public function index($username) {
		$playlist_model = new Playlist_model();
		$review_model = new Review_model();
		$user_model = new User_model();
		$duel_result_model = new Duel_result_model();
		$data = $this->getUserViewData();
		if($data['user'] = $user_model->get_user_profile($username)) {
			$data['playlists'] = $playlist_model->get_Playlists_from_User($data['user']->idUser, true);
			$data['reviews'] = $review_model->get_Review_for_user($data['user']->idUser);
			$data['nbDuelz'] = $duel_result_model->get_number_of_duels_User($data['user']->idUser);
		}
		$data['view'] = 'user_profile.php';
		$this->setSocialMeta($data);
		echo view('template.php', $data);
	}

	function setSocialMeta(&$data) {
		if($user = $data['user']) {
			$nbReviews = count($data['reviews']);
			$ndSharedPlaylists = count($data['playlists']);
			$nbDuelz = $data['nbDuelz'];
			$data['page_description'] = "{$user->userName}'s profile: $nbReviews track reviews, {$nbDuelz} duelz taken and $ndSharedPlaylists shared playlists.";
		} else {
			$data['page_description'] = 'User profile not found';
		}
	}

	//Ajax GET partial view
	public function playlistDetails($idPlaylist) {
		$playlist_model = new Playlist_model();
		$playlist_item_model = new Playlist_item_model();
		$data = $this->getUserViewData();
		
		$playlist = $playlist_model->get_Playlist($idPlaylist);
		if($playlist && $playlist->public)
		{
			$data['tracks'] = $playlist_item_model->get_PlaylistItems_for_Playlist($idPlaylist);
			$data['playlist'] = $playlist;
			echo view('user_profile_playlist_dialog.php', $data);
		}
	}
}
