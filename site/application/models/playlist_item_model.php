<?php
class Playlist_Item_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function get_Playlist_item($idPlaylist = FALSE, $idTrack = FALSE) {
		$this->db->join('Track', 'PlaylistItem.idTrack = Track.idTrack', 'inner');
		$this->db->join('Playlist', 'PlaylistItem.idPlaylist = Playlist.idPlaylist', 'inner');
		if ($idPlaylist === FALSE && $idTrack === FALSE) { // on n'a rien
			$query = $this->db->get('PlaylistItem');
			return $query->result_array();
		} else { // on a les deux, yay!
			$this->db->where('PlaylistItem.idPlaylist', $idPlaylist);
			$this->db->where('PlaylistItem.idTrack', $idTrack);
			$query = $this->db->get('PlaylistItem');
			return $query->row();
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_PlaylistItem_for_Track($idTrack = FALSE) {
		$this->db->join('Track', 'PlaylistItem.idTrack = Track.idTrack', 'inner');
		if($idTrack === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('PlaylistItem');
			return $query->result_array();
		} else {
			$this->db->where('PlaylistItem.idTrack', $idTrack); 
			$query = $this->db->get('PlaylistItem');
			return $query->result_array();
		}
	}
	
	public function get_PlaylistItem_for_Playlist($idPlaylist = FALSE) {
		$this->db->join('Playlist', 'PlaylistItem.idPlaylist = Playlist.idPlaylist', 'inner');
		if($idPlaylist === FALSE) { //devrait pas arriver...?
			$query = $this->db->get('PlaylistItem');
			return $query->result_array();
		} else {
			$this->db->where('PlaylistItem.idPlaylist', $idPlaylist); 
			$query = $this->db->get('PlaylistItem');
			return $query->result_array();
		}
	}

	public function set_Playlist_item($idPlaylist, $idTrack, $position) {
		$data = array(
			'idPlaylist' => $idPlaylist,
			'idTrack' => $idTrack,
			'position' => $position
		);

		return $this->db->insert('PlaylistItem', $data);
	}

	public function delete_Playlist_item($idPlaylist, $idTrack) {
		return $this->db->delete('PlaylistItem', array('idPlaylist' => $idPlaylist, 'idTrack' => $idTrack));
	}
}
