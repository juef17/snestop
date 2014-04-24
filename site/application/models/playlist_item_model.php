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
		} // si on a juste 1 des deux, voir les m�thodes ci-bas
	}
	
	public function get_PlaylistItems_for_Track($idTrack) {
		$this->db->join('Track', 'PlaylistItem.idTrack = Track.idTrack', 'inner');
		$this->db->where('PlaylistItem.idTrack', $idTrack); 
		$query = $this->db->get('PlaylistItem');
		return $query->result_array();
	}
	
	public function get_PlaylistItems_for_Playlist($idPlaylist) {
		$this->db->select('Track.idTrack, Track.title, Track.length, Track.screenshotURL, Game.titleEng AS gameTitleEng, PlaylistItem.position as position');
		$this->db->join('Track', 'PlaylistItem.idTrack = Track.idTrack', 'INNER');
		$this->db->join('Game', 'Track.idGame = Game.idGame', 'INNER');
		$this->db->where('PlaylistItem.idPlaylist', $idPlaylist);
		$this->db->order_by('position', 'asc');
		$query = $this->db->get('PlaylistItem');
		return $query->result();  //no bool in result so no need for conversion
	}

	public function set_Playlist_item($idPlaylist, $idTrack) {
		$this->db->select_max('position', 'position');
		$this->db->where('idPlaylist', $idPlaylist);
		$query = $this->db->get('PlaylistItem');
		$position = $query->row()->position;
		if($position == NULL)
			$position = 0;
		else
			$position++;
		
		$data = array(
			'idPlaylist' => $idPlaylist,
			'idTrack' => $idTrack,
			'position' => $position
		);

		return $this->db->insert('PlaylistItem', $data);
	}

	public function updatePosition($idPlaylist, $idTrack, $newPosition) {
		$this->db->where('idPlaylist', $idPlaylist);
		$this->db->where('idTrack', $idTrack);
		return $this->db->update('PlaylistItem', array('position' => $newPosition));
	}

	public function playlistItemExists($idPlaylist, $idTrack) {
		$this->db->select('idPlaylist');
		$this->db->where('idPlaylist', $idPlaylist);
		$this->db->where('idTrack', $idTrack);
		$this->db->from('PlaylistItem');
		$this->db->limit(1);
		return $this->db->count_all_results() > 0;
	}

	public function delete_Playlist_item($idPlaylist, $idTrack) {
		return $this->db->delete('PlaylistItem', array('idPlaylist' => $idPlaylist, 'idTrack' => $idTrack));
	}
}
