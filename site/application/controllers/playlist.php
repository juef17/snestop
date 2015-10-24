<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Playlist extends Secure_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Playlist_model','',TRUE);
		$this->load->model('Track_model','',TRUE);
		$this->load->model('Playlist_Item_model','',TRUE);
	}

	//Ajax POST partial view
	public function create() {
		$data = array();
		$this->setValidationRules();
		if($this->form_validation->run()) {
			$name = $this->input->post('name');
			$public = $this->input->post('public');
			$data['idPlaylist'] = $this->Playlist_model->set_Playlist($_SESSION['loggedUser']->idUser, $name, 0, $public, FALSE, FALSE);

			if($idPlaylistSource = $this->input->post('source'))
				foreach($this->Playlist_Item_model->get_PlaylistItems_for_Playlist($idPlaylistSource) as $track)
					$this->Playlist_Item_model->set_Playlist_item($data['idPlaylist'], $track->idTrack);
		}
		$this->load->view('includes/player_create_playlist_dialog_content.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean|callback_verifyPlaylistName');
		$this->form_validation->set_rules('source', 'Source', 'trim|xss_clean|callback_verifySourcePlaylist');
		$this->form_validation->set_rules('public', 'Public', 'xss_clean');
	}

	public function verifyPlaylistName($name) {
		$this->form_validation->set_message('verifyPlaylistName', 'You already have a playlist with that name.');
		return !$this->Playlist_model->userHasPlaylistWithName($_SESSION['loggedUser']->idUser, $name);
	}

	public function verifySourcePlaylist($idPlaylist) {
		$this->form_validation->set_message('verifySourcePlaylist', 'Something\'s wrong with the source playlist: it\'s either not yours and not public, or it does not exist.');

		if($idPlaylist == null)
			return true;
		else {
			$originalPlaylist = $this->Playlist_model->get_Playlist($idPlaylist);

			return $originalPlaylist
				&& ($originalPlaylist->idUser == $_SESSION['loggedUser']->idUser || $originalPlaylist->public);
		}
	}

	public function playlists($mode) {
		$data['playlists'] = $this->Playlist_model->get_Playlists_from_User($_SESSION['loggedUser']->idUser);
		$data['mode'] = $mode;
		$this->load->view('includes/playlists_dropdown_content.php', $data);
	}

	//Ajax GET partial view
	public function playlistDetails($idPlaylist) {
		//check if playlist belongs to logged user, else check if public.
		$playlist = $this->Playlist_model->get_Playlist($idPlaylist);

		if($playlist
			&& ($playlist->idUser == $_SESSION['loggedUser']->idUser
					|| $playlist->public))
		{
			$tracks = $this->Playlist_Item_model->get_PlaylistItems_for_Playlist($idPlaylist);
			$data = $this->getUserViewData();
			$data['playlist'] = $playlist;
			$data['playlistItems'] = $tracks;
			$this->load->view('includes/playlist_content.php', $data);
		} else {
			//faudrait faire dequoi, mais anyways on se
			//rend pas ici normalement. c'est juste un hack proof ;)
		}
	}

	//Ajax POST
	public function setPublic() {
		$idPlaylist = $this->input->post('idPlaylist');
		$public = $this->input->post('public') == 'true';
		
		$playlist = $this->Playlist_model->get_Playlist($idPlaylist);
		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			$data['success'] = $this->Playlist_model->setPublic($idPlaylist, $public);
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You can\'t modifiy someone else\'s playlist.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function delete() {
		$idPlaylist = $this->input->post('idPlaylist');
		$playlist = $this->Playlist_model->get_Playlist($idPlaylist);

		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			$data['success'] = $this->Playlist_model->delete_Playlist($idPlaylist);
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You can\'t delete someone else\'s playlist.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function addPlaylistItem() {
		$idPlaylist = $this->input->post('idPlaylist');
		$idTrack = $this->input->post('idTrack');
		$playlist = $this->Playlist_model->get_Playlist($idPlaylist);
		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			if($this->Playlist_Item_model->playlistItemExists($idPlaylist, $idTrack)) {
				$data['success'] = FALSE;
				$data['message'] = 'This track is already in this playlist';
			} else {
				$data['success'] = $this->Playlist_Item_model->set_Playlist_item($idPlaylist, $idTrack);
				$data['message'] = 'An unexpected error occured, sorry :(';
			}
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You can\'t modify someone else\'s playlist.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function createSimple() {
		$playlistName = $this->input->post('playlistName');
		if(!$this->Playlist_model->userHasPlaylistWithName($_SESSION['loggedUser']->idUser, $playlistName)) {
			$data['success'] = $this->Playlist_model->set_Playlist($_SESSION['loggedUser']->idUser, $playlistName, 0, FALSE, FALSE, FALSE);
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You already have a playlist with that name.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function savePositions() {
		$idPlaylist = $this->input->post('idPlaylist');
		$idTracks = $this->input->post('idTracks');
		$playlist = $this->Playlist_model->get_Playlist($idPlaylist);
		
		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			for($i = 0; $i < count($idTracks); $i++) {
				$data['success'] = $this->Playlist_Item_model->updatePosition($idPlaylist, $idTracks[$i], $i);
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
		$idPlaylist = $this->input->post('idPlaylist');
		$idTrack = $this->input->post('idTrack');
		$playlist = $this->Playlist_model->get_Playlist($idPlaylist);
		
		if($playlist && $playlist->idUser == $_SESSION['loggedUser']->idUser) {
			$data['success'] = $this->Playlist_Item_model->delete_Playlist_item($idPlaylist, $idTrack);
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'You can\'t modify someone else\'s playlist.';
		}
		
		echo json_encode($data);
	}

	//Ajax POST
	public function copyPlaylist() {
		$idPlaylist = $this->input->post('idPlaylist');
		//check if playlist belongs to logged user and check if public.
		$originalPlaylist = $this->Playlist_model->get_Playlist($idPlaylist);

		if($originalPlaylist
			&& ($originalPlaylist->idUser != $_SESSION['loggedUser']->idUser)
			&& $originalPlaylist->public)
		{
			$this->Playlist_model->set_Playlist($_SESSION['loggedUser']->idUser, $name, 0, false);
			
			$idNewPlaylist = $this->db->insert_id();
			$data['playlistItems'] = $data['tracks'] = $this->Playlist_Item_model->get_PlaylistItems_for_Playlist($idPlaylist);
			$this->load->view('includes/playlist_content.php', $data);
		} else {
			//faudrait faire dequoi, mais anyways on se
			//rend pas ici normalement. c'est juste un hack proof ;)
		}
	}
}
?>
