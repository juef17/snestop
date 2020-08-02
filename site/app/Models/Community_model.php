<?php namespace App\Models;

use CodeIgniter\Model;

class Community_model extends Model
{
    protected $table = 'Community';

	public function get_Community($idCommunity = FALSE) {
		$builder = $this->db->table('Community');
		if ($idCommunity === FALSE) {
			$query = $builder->get();
			return $query->getResult('array');
		} else {
			$query = $builder->getWhere(['idCommunity' => $idCommunity]);
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		}
	}

	public function set_Community($name, $token, $URL) {
		$data = array(
			'name' => $name,
			'token' => $token,
			'URL' => $URL
		);
		$builder = $this->db->table('Community');
		$builder->insert($data);
		return $builder->get();
	}

	public function delete_Community($idCommunity) {
		$builder = $this->db->table('Community');
		$builder->delete(['idCommunity' => $idCommunity]);
		return $builder->get();
	}
	
	public function update_Community($idCommunity, $name, $token, $URL) {
		$builder = $this->db->table('Community');
		$builder->where('Community.idCommunity', $idCommunity);
		return $builder->update(['name' => $name, 'token' => $token, 'URL' => $URL]);
	}

	public function get_communities_for_combobox() {
		$builder = $this->db->table('Community');
		$builder->select('idCommunity, name');
		$builder->orderBy('name', 'asc');
		$query = $builder->get();
		return $query->getResult();
	}

}
