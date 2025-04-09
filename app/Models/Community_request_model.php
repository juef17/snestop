<?php namespace App\Models;

use CodeIgniter\Model;

class Community_request_model extends Model
{
    protected $table = 'CommunityRequest';
	
	public function get_Community_request($idCommunityRequest = FALSE) {
		$builder = $this->db->table('CommunityRequest');
		if ($idCommunityRequest === FALSE) {
			$query = $builder->get();
			return $query->getResult();
		} else {
			$query = $builder->getWhere(['idCommunityRequest' => $idCommunityRequest]);
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		}
	}

	public function get_Community_requests_for_dashboard() {
		$builder = $this->db->table('CommunityRequest');
		$builder->join('User', 'CommunityRequest.idUserRequester = User.idUser', 'inner');
		$query = $builder->get();
		return $query->getResult();
	}

	public function set_Community_request($idUser, $URL, $name) {
		$data = array(
			'URL' => $URL,
			'name' => $name,
			'idUserRequester' => $idUser
		);

		$builder = $this->db->table('CommunityRequest');
		return $builder->insert($data);
	}

	public function delete_Community_request($idCommunityRequest) {
		$builder = $this->db->table('CommunityRequest');
		return $builder->delete(['idCommunityRequest' => $idCommunityRequest]);
	}
}
