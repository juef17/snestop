<?php namespace App\Models;

use CodeIgniter\Model;

class Playlist_item_model extends Model
{
    protected $table = 'PlaylistItem';
	
	public function get_Playlist_item($idPlaylist = FALSE, $idTrack = FALSE) {
		$builder = $this->db->table('PlaylistItem');
		$builder->join('Track', 'PlaylistItem.idTrack = Track.idTrack', 'inner');
		$builder->join('Playlist', 'PlaylistItem.idPlaylist = Playlist.idPlaylist', 'inner');
		if ($idPlaylist === FALSE && $idTrack === FALSE) { // on n'a rien
			$query = $builder->get();
			$retval = array();
			foreach($query->getResult() as $row) {
				$retval[] = $this->getTrackFromRow($row);
			}
			return $retval;
		} else { // on a les deux, yay!
			$builder->where('PlaylistItem.idPlaylist', $idPlaylist);
			$builder->where('PlaylistItem.idTrack', $idTrack);
			$query = $builder->get();
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			if($row)
				return $this->getTrackFromRow($row);
			else
				return null;
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_PlaylistItems_for_Track($idTrack) {
		$builder = $this->db->table('PlaylistItem');
		$builder->join('Track', 'PlaylistItem.idTrack = Track.idTrack', 'inner');
		$builder->where('PlaylistItem.idTrack', $idTrack); 
		$query = $builder->get();
		$retval = array();
		foreach($query->getResult() as $row) {
			$retval[] = $this->getTrackFromRow($row);
		}
		return $retval;
	}
	
	public function get_PlaylistItems_for_Playlist($idPlaylist) {
		$builder = $this->db->table('PlaylistItem');
		$builder->select('Track.idTrack, Track.title, Track.length, Track.isScreenshotSet, Game.idGame, Game.titleEng AS gameTitleEng, PlaylistItem.position as position');
		$builder->join('Track', 'PlaylistItem.idTrack = Track.idTrack', 'INNER');
		$builder->join('Game', 'Track.idGame = Game.idGame', 'INNER');
		$builder->where('PlaylistItem.idPlaylist', $idPlaylist);
		$builder->orderBy('position', 'asc');
		$query = $builder->get();
		$retval = array();
		foreach($query->getResult() as $row) {
			$retval[] = $this->getTrackFromRow($row);
		}
		return $retval;
	}

	public function set_Playlist_item($idPlaylist, $idTrack) {
		$builder = $this->db->table('PlaylistItem');
		$builder->selectMax('position', 'position');
		$builder->where('idPlaylist', $idPlaylist);
		$query = $builder->get();
		foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
		$position = $row->position;
		if($position == NULL)
			$position = 0;
		else
			$position++;
		
		$data = array(
			'idPlaylist' => $idPlaylist,
			'idTrack' => $idTrack,
			'position' => $position
		);

		return $builder->insert($data);
	}

	private function getTrackFromRow($row) {
		$row->isScreenshotSet = ord($row->isScreenshotSet) == 1 || $row->isScreenshotSet == 1;
		return $row;
	}

	public function updatePosition($idPlaylist, $idTrack, $newPosition) {
		$builder = $this->db->table('PlaylistItem');
		$builder->where('idPlaylist', $idPlaylist);
		$builder->where('idTrack', $idTrack);
		return $builder->update(['position' => $newPosition]);
	}

	public function playlistItemExists($idPlaylist, $idTrack) {
		$builder = $this->db->table('PlaylistItem');
		$builder->select('idPlaylist');
		$builder->where('idPlaylist', $idPlaylist);
		$builder->where('idTrack', $idTrack);
		$builder->limit(1);
		return $builder->countAllResults() > 0;
	}

	public function delete_Playlist_item($idPlaylist, $idTrack) {
		$builder = $this->db->table('PlaylistItem');
		if($deletedItem = $this->get_Playlist_item($idPlaylist, $idTrack)) {
			$retval = $builder->delete(['idPlaylist' => $idPlaylist, 'idTrack' => $idTrack]);
			$items = $this->get_PlaylistItems_for_Playlist($idPlaylist);
			foreach($items as $item)
				if($item->position > $deletedItem->position)
					$retval &= $this->updatePosition($idPlaylist, $item->idTrack, $item->position - 1);
					
			return $retval;
		} else {
			return FALSE;
		}
	}
}
