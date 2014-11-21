<?php
class Playlist_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Playlist($idPlaylist = FALSE) {
		if ($idPlaylist === FALSE) {
			$query = $this->db->get('Playlist');
			$retval = array();
			foreach($query->result() as $row)
				$retval[] = $this->getPlaylistFromRow($row);
				
			return $retval;
		} else {
			$query = $this->db->get_where('Playlist', array('idPlaylist' => $idPlaylist));
			if($this->db->count_all_results() > 0)
				return $this->getPlaylistFromRow($query->row());
			else
				return NULL;
		}
	}

	public function get_Playlists_from_User($idUser, $publicOnly = false) {
		$this->db->where('idUser', $idUser);
		if($publicOnly)
			$this->db->where('public', 1);
		$query = $this->db->get('Playlist');
		$retval = array();
		foreach($query->result() as $row)
			$retval[] = $this->getPlaylistFromRow($row);
			
		return $retval;
	}

	public function set_Playlist($idUser, $name, $nbPlays, $public) {
		$data = array(
			'idUser' => $idUser,
			'name' => $name,
			'nbPlays' => $nbPlays,
			'public' => $public
		);
		if($this->db->insert('Playlist', $data))
			return $this->db->insert_id();
		else
			return FALSE;
	}

	public function setPublic($idPlaylist, $public) {
		$this->db->where('idPlaylist', $idPlaylist);
		$this->db->set('public', $public);
		if($this->db->update('Playlist'))
			return TRUE;
		else
			return FALSE;
	}

	public function increment_nbPlays($idPlaylist) {
		$this->db->where('idPlaylist', $idPlaylist);
		$this->db->set('nbPlays', 'nbPlays+1', FALSE);
		$this->db->update('Playlist');
	}
	
	public function get_Playlist_score($idPlaylist) {
		$this->db->where('idPlaylist', $idPlaylist);
		$this->db->where('voteType', 1);
		$this->db->from('PlaylistVote');
		$upVotes = $this->db->count_all_results();
		$this->db->where('idPlaylist', $idPlaylist);
		$this->db->where('voteType', -1);
		$this->db->from('PlaylistVote');
		$downVotes = $this->db->count_all_results();
		return ($upVotes - $downVotes);
	}

	public function delete_Playlist($idPlaylist) {
		return $this->db->delete('Playlist', array('idPlaylist' => $idPlaylist));
	}

	public function userHasPlaylistWithName($idUser, $playlistName) {
		$this->db->where('idUser', $idUser);
		$this->db->where('name', $playlistName);
		$this->db->from('Playlist');
		return $this->db->count_all_results() > 0;
	}

	private function getPlaylistFromRow($row) {
		$row->public = ord($row->public) == 1 || $row->public == 1;
		return $row;
	}
}
