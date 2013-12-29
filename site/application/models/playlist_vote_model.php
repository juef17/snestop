<?php
class Playlist_Vote_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Playlist_Vote($idPlaylist = FALSE, $idUser = FALSE) {
		$this->db->join('User', 'PlaylistVote.idUser = User.idUser', 'inner');
		$this->db->join('Playlist', 'PlaylistVote.idPlaylist = Playlist.idPlaylist', 'inner');
		if ($idPlaylist === FALSE && $idUser === FALSE) { // on n'a rien
			$query = $this->db->get('PlaylistVote');
			return $query->result_array();
		} else { // on a les deux, yay!
			$this->db->where('PlaylistVote.idPlaylist', $idPlaylist);
			$this->db->where('PlaylistVote.idUser', $idUser);
			$query = $this->db->get('PlaylistVote');
			return $query->row();
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_Playlist_Vote_for_User($idUser = FALSE) {
		$this->db->join('User', 'PlaylistVote.idUser = User.idUser', 'inner');
		if($idUser === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('PlaylistVote');
			return $query->result_array();
		} else {
			$this->db->where('PlaylistVote.idUser', $idUser); 
			$query = $this->db->get('PlaylistVote');
			return $query->result_array();
		}
	}
	
	public function get_Playlist_Vote_for_Playlist($idPlaylist = FALSE) {
		$this->db->join('Playlist', 'PlaylistVote.idPlaylist = Playlist.idPlaylist', 'inner');
		if($idPlaylist === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('PlaylistVote');
			return $query->result_array();
		} else {
			$this->db->where('PlaylistVote.idPlaylist', $idPlaylist); 
			$query = $this->db->get('PlaylistVote');
			return $query->result_array();
		}
	}

	public function set_Playlist_Vote($idPlaylist, $idUser, $voteType) {
		$data = array(
			'idPlaylist' => $idPlaylist,
			'idUser' => $idUser,
			'voteType' => $voteType
		);

		return $this->db->insert('PlaylistVote', $data);
	}

	public function delete_Playlist_Vote($idPlaylist, $idUser) {
		return $this->db->delete('PlaylistVote', array('idPlaylist' => $idPlaylist, 'idUser' => $idUser));
	}
}
