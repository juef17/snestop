<?php namespace App\Models;

use CodeIgniter\Model;

class Playlist_model extends Model
{
    protected $table = 'Playlist';
	
	public function get_Playlist($idPlaylist = FALSE) {
		$builder = $this->db->table('Playlist');
		if ($idPlaylist === FALSE) {
			$query = $builder->get();
			$retval = array();
			foreach($query->getResult() as $row)
				$retval[] = $this->getPlaylistFromRow($row);
				
			return $retval;
		} else {
			$query = $builder->getWhere(['idPlaylist' => $idPlaylist]);
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			if($builder->countAllResults(false) > 0)
				return $this->getPlaylistFromRow($row);
			else
				return NULL;
		}
	}

	public function get_Playlists_from_User($idUser, $publicOnly = false) {
		$builder = $this->db->table('Playlist');
		$builder->where('idUser', $idUser);
		if($publicOnly)
			$builder->where('public', 1);
		$query = $builder->get();
		$retval = array();
		foreach($query->getResult() as $row)
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
		$builder = $this->db->table('Playlist');
		if($builder->insert($data))
			return $this->db->insertId();
		else
			return FALSE;
	}

	public function setPublic($idPlaylist, $public) {
		$builder = $this->db->table('Playlist');
		$builder->where('idPlaylist', $idPlaylist);
		$builder->set('public', $public);
		if($builder->update())
			return TRUE;
		else
			return FALSE;
	}

	public function increment_nbPlays($idPlaylist) {
		$builder = $this->db->table('Playlist');
		$builder->where('idPlaylist', $idPlaylist);
		$builder->set('nbPlays', 'nbPlays+1', FALSE);
		$builder->update();
	}
	
	public function get_Playlist_score($idPlaylist) {
		$builder = $this->db->table('PlaylistVote');
		$builder->where('idPlaylist', $idPlaylist);
		$builder->where('voteType', 1);
		$upVotes = $builder->countAllResults();
		$builder->where('idPlaylist', $idPlaylist);
		$builder->where('voteType', -1);
		$downVotes = $builder->countAllResults();
		return ($upVotes - $downVotes);
	}

	public function delete_Playlist($idPlaylist) {
		$builder = $this->db->table('Playlist');
		return $builder->delete(['idPlaylist' => $idPlaylist]);
	}

	public function userHasPlaylistWithName($idUser, $playlistName) {
		$builder = $this->db->table('Playlist');
		$builder->where('idUser', $idUser);
		$builder->where('name', $playlistName);
		return $builder->countAllResults() > 0;
	}

	private function getPlaylistFromRow($row) {
		$row->public = ord($row->public) == 1 || $row->public == 1;
		return $row;
	}
}
