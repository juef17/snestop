<?php namespace App\Models;

use CodeIgniter\Model;

class Mistake_request_model extends Model
{
    protected $table = 'MistakeRequest';
	
	public function get_Mistake_request($idMistakeRequest = FALSE) {
		$builder = $this->db->table('MistakeRequest');
		$builder->join('User', 'MistakeRequest.idUserRequester = User.idUser', 'inner');
		if ($idMistakeRequest === FALSE) {
			$query = $builder->get('MistakeRequest');
			return $query->getResult();
		} else {
			$query = $builder->getWhere(['idMistakeRequest' => $idMistakeRequest]);
			foreach ($query->getResult() as $row) break; //TODO esti que c'est lette
			return $row;
		}
	}

	public function set_Mistake_request($idUserRequester, $text) {
		$data = array(
			'text' => $text,
			'idUserRequester' => $idUserRequester
		);

		$builder = $this->db->table('MistakeRequest');
		return $builder->insert($data);
	}

	public function delete_Mistake_request($idMistakeRequest) {
		$builder = $this->db->table('MistakeRequest');
		return $builder->delete(['idMistakeRequest' => $idMistakeRequest]);
	}
}
