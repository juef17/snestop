<?php
class Playlist_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Playlist($idPlaylist = FALSE) {
		$this->db->join('User', 'Playlist.idUser = User.idUser', 'inner');
		if ($idPlaylist === FALSE) {
			$query = $this->db->get('Playlist');
			return $query->result();
		} else {
			$query = $this->db->get_where('Playlist', array('idPlaylist' => $idPlaylist));
			return $query->row();
		}
	}

	public function get_Playlist_from_User($idUser = FALSE) {
		if ($idUser === FALSE) {
			$query = $this->db->get('Playlist');
			return $query->result();
		} else {
			$query = $this->db->get_where('Playlist', array('idUser' => $idUser));
			return $query->row();
		}
	}

	public function set_Playlist($idUser, $name, $nbPlays, $public, $randomize, $loop) {
		$data = array(
			'idUser' => $idUser,
			'name' => $name,
			'nbPlays' => $nbPlays,
			'public' => $public,
			'randomize' => $randomize,
			'loop' => $loop
		);
		return $this->db->insert('Playlist', $data);
	}

	public function increment_nbPlays($idPlaylist) {
		$this->db->where('idPlaylist', $idPlaylist);
		$this->db->set('nbPlays', 'nbPlays+1', FALSE);
		$this->db->update('Playlist');
	}

	public function delete_Playlist($idPlaylist) {
		return $this->db->delete('Playlist', array('idPlaylist' => $idPlaylist));
	}
}
