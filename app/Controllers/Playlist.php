<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Track_model;
use App\Models\Playlist_model;
use App\Models\Playlist_item_model;

helper('utility_helper');

class Playlist extends Secure_controller {

	//Ajax POST partial view
	public function create() {
		$playlist_model = new Playlist_model();
		$playlist_item_model = new Playlist_item_model();
		$data = array();
		$this->setValidationRules();
		if($this->form_validation->run()) {
			$name = $this->request->getVar('name');
			$public = $this->request->getVar('public');
			$data['idPlaylist'] = $playlist_model->set_Playlist($_SESSION['loggedUser']->idUser, $name, 0, $public, FALSE, FALSE);

			if($idPlaylistSource = $this->request->getVar('source'))
				foreach($playlist_item_model->get_PlaylistItems_for_Playlist($idPlaylistSource) as $track)
					$playlist_item_model->set_Playlist_item($data['idPlaylist'], $track->idTrack);
		}
		echo view('includes/player_create_playlist_dialog_content.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean|callback_verifyPlaylistName');
		$this->form_validation->set_rules('source', 'Source', 'trim|xss_clean|callback_verifySourcePlaylist');
		$this->form_validation->set_rules('public', 'Public', 'xss_clean');
	}

	public function verifyPlaylistName($name) {
		$playlist_model = new Playlist_model();
		$this->form_validation->set_message('verifyPlaylistName', 'You already have a playlist with that name.');
		return !$playlist_model->userHasPlaylistWithName($_SESSION['loggedUser']->idUser, $name);
	}

	public function verifySourcePlaylist($idPlaylist) {
		$playlist_model = new Playlist_model();
		$this->form_validation->set_message('verifySourcePlaylist', 'Something\'s wrong with the source playlist: it\'s either not yours and not public, or it does not exist.');

		if($idPlaylist == null)
			return true;
		else {
			$originalPlaylist = $playlist_model->get_Playlist($idPlaylist);

			return $originalPlaylist
				&& ($originalPlaylist->idUser == $_SESSION['loggedUser']->idUser || $originalPlaylist->public);
		}
	}

	public function playlists($mode) {
		$playlist_model = new Playlist_model();
		$data['playlists'] = $playlist_model->get_Playlists_from_User($_SESSION['loggedUser']->idUser);
		$data['mode'] = $mode;
		echo view('includes/playlists_dropdown_content.php', $data);
	}

	//Ajax GET partial view
	public function playlistDetails($idPlaylist) {
		$idPlaylist = explode(',', urldecode($idPlaylist));
		if(count($idPlaylist) == 1)
			$this->playlistDetailsForIdPlaylist($idPlaylist[0]);
		else
			$this->playlistDetailsForIdTracks($idPlaylist);
	}

	function playlistDetailsForIdPlaylist($idPlaylist) {
		$playlist_model = new Playlist_model();
		$playlist_item_model = new Playlist_item_model();
		//check if playlist belongs to logged user, else check if public.
		$playlist = $playlist_model->get_Playlist($idPlaylist);

		if($playlist
			&& ($playlist->idUser == $_SESSION['loggedUser']->idUser
					|| $playlist->public))
		{
			$tracks = $playlist_item_model->get_PlaylistItems_for_Playlist($idPlaylist);
			$data = $this->getUserViewData();
			$data['playlist'] = $playlist;
			$data['playlistItems'] = $tracks;
			echo view('includes/playlist_content.php', $data);
		} else {
			//faudrait faire dequoi, mais anyways on se
			//rend pas ici normalement. c'est juste un hack proof ;)
		}
	}

	function playlistDetailsForIdTracks($idTracks) {
		$track_model = new Track_model();
		$data = $this->getUserViewData();
		$tracks = $track_model->getTracksForPlaylist($idTracks);
		$data['playlistItems'] = $tracks;
		echo view('includes/playlist_content.php', $data);
	}
	
	public function playlistItemsForIdTracks($idTracks) {
		$track_model = new Track_model();
		$data = $this->getUserViewData();
		$tracks = $track_model->getTracksForPlaylist($idTracks);
		$data['playlistItems'] = $tracks;
		$data['playlistEditable'] = TRUE;
		echo view('includes/playlist_items.php', $data);
	}

	//Ajax POST
	public function setPublic() {
		$playlist_model = new Playlist_model();
		$idPlaylist = $this->request->getVar('idPlaylist');
		$public = $this->request->getVar('public') == 'true';
		
		$playlist = $playlist_model->get_Playlist($idPlaylist);
		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			$data['success'] = $playlist_model->setPublic($idPlaylist, $public);
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You can\'t modifiy someone else\'s playlist.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function delete() {
		$playlist_model = new Playlist_model();
		$idPlaylist = $this->request->getVar('idPlaylist');
		$playlist = $playlist_model->get_Playlist($idPlaylist);

		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			$data['success'] = $playlist_model->delete_Playlist($idPlaylist);
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You can\'t delete someone else\'s playlist.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function addPlaylistItems() {
		$playlist_model = new Playlist_model();
		$playlist_item_model = new Playlist_item_model();
		$idPlaylist = $this->request->getVar('idPlaylist');
		$idTracks = $this->request->getVar('idTracks');
		$playlist = $playlist_model->get_Playlist($idPlaylist);
		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			$tracksAreValid = TRUE;
			foreach($idTracks as $idTrack) {
				if($playlist_item_model->playlistItemExists($idPlaylist, $idTrack)) {
					$data['success'] = FALSE;
					$data['message'] = 'One or more tracks are already in this playlist';
					$tracksAreValid = FALSE;
					break;
				}
			}
			if($tracksAreValid) {
				foreach($idTracks as $idTrack) {
					$data['success'] = $playlist_item_model->set_Playlist_item($idPlaylist, $idTrack);
					$data['message'] = 'An unexpected error occured, sorry :(';
				}
			}
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You can\'t modify someone else\'s playlist.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function createSimple() {
		$playlist_model = new Playlist_model();
		$playlistName = $this->request->getVar('playlistName');
		if(!$playlist_model->userHasPlaylistWithName($_SESSION['loggedUser']->idUser, $playlistName)) {
			$data['success'] = $playlist_model->set_Playlist($_SESSION['loggedUser']->idUser, $playlistName, 0, FALSE, FALSE, FALSE);
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You already have a playlist with that name.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function savePositions() {
		$playlist_item_model = new Playlist_item_model();
		$playlist_model = new Playlist_model();
		$idPlaylist = $this->request->getVar('idPlaylist');
		$idTracks = $this->request->getVar('idTracks');
		$playlist = $playlist_model->get_Playlist($idPlaylist);
		
		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			for($i = 0; $i < count($idTracks); $i++) {
				$data['success'] = $playlist_item_model->updatePosition($idPlaylist, $idTracks[$i], $i);
				if(!$data['success'])
					break;
			}
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You can\'t modify someone else\'s playlist.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function deleteItem() {
		$playlist_item_model = new Playlist_item_model();
		$playlist_model = new Playlist_model();
		$idPlaylist = $this->request->getVar('idPlaylist');
		$idTrack = $this->request->getVar('idTrack');
		$playlist = $playlist_model->get_Playlist($idPlaylist);
		
		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			$data['success'] = $playlist_item_model->delete_Playlist_item($idPlaylist, $idTrack);
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You can\'t modify someone else\'s playlist.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function copyPlaylist() {
		$playlist_item_model = new Playlist_item_model();
		$playlist_model = new Playlist_model();
		$idPlaylist = $this->request->getVar('idPlaylist');
		//check if playlist belongs to logged user and check if public.
		$originalPlaylist = $playlist_model->get_Playlist($idPlaylist);

		if($originalPlaylist
			&& ($originalPlaylist->idUser != $_SESSION['loggedUser']->idUser)
			&& $originalPlaylist->public)
		{
			$playlist_model->set_Playlist($_SESSION['loggedUser']->idUser, $name, 0, false);
			
			$idNewPlaylist = $this->db->insert_id();
			$data['playlistItems'] = $data['tracks'] = $playlist_item_model->get_PlaylistItems_for_Playlist($idPlaylist);
			echo view('includes/playlist_content.php', $data);
		} else {
			//faudrait faire dequoi, mais anyways on se
			//rend pas ici normalement. c'est juste un hack proof ;)
		}
	}
}
