<?php namespace App\Models;

use CodeIgniter\Model;

class Playlist_vote_model extends Model
{
    protected $table = 'PlaylistVote';
	
	public function get_Playlist_Vote($idPlaylist = FALSE, $idUser = FALSE) {
		$builder = $this->db->table('PlaylistVote');
		$builder->join('User', 'PlaylistVote.idUser = User.idUser', 'inner');
		$builder->join('Playlist', 'PlaylistVote.idPlaylist = Playlist.idPlaylist', 'inner');
		if ($idPlaylist === FALSE && $idUser === FALSE) { // on n'a rien
			$query = $builder->get();
			return $query->getResult('array');
		} else { // on a les deux, yay!
			$builder->where('PlaylistVote.idPlaylist', $idPlaylist);
			$builder->where('PlaylistVote.idUser', $idUser);
			$query = $builder->get();
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		} // si on a juste 1 des deux, voir les méthodes ci-bas
	}
	
	public function get_Playlist_Vote_for_User($idUser = FALSE) {
		$builder = $this->db->table('PlaylistVote');
		$builder->join('User', 'PlaylistVote.idUser = User.idUser', 'inner');
		if($idUser === FALSE) { //devrait pas arriver...?
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$builder->where('PlaylistVote.idUser', $idUser); 
			$query = $builder->get();
			return $query->getResult('array');
		}
	}
	
	public function get_Playlist_Vote_for_Playlist($idPlaylist = FALSE) {
		$builder = $this->db->table('PlaylistVote');
		$builder->join('Playlist', 'PlaylistVote.idPlaylist = Playlist.idPlaylist', 'inner');
		if($idPlaylist === FALSE) { //devrait pas arriver...?
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$builder->where('PlaylistVote.idPlaylist', $idPlaylist); 
			$query = $builder->get();
			return $query->getResult('array');
		}
	}

	public function set_Playlist_Vote($idPlaylist, $idUser, $voteType) {
		$data = array(
			'idPlaylist' => $idPlaylist,
			'idUser' => $idUser,
			'voteType' => $voteType
		);

		$builder = $this->db->table('PlaylistVote');
		return $builder->insert($data);
	}

	public function delete_Playlist_Vote($idPlaylist, $idUser) {
		$builder = $this->db->table('PlaylistVote');
		return $builder->delete(['idPlaylist' => $idPlaylist, 'idUser' => $idUser]);
	}
}
