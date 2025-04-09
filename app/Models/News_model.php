<?php namespace App\Models;

use CodeIgniter\Model;

helper('date');

class News_model extends Model
{
    protected $table = 'News';

	public function get_news($idnews = FALSE) {
		$builder = $this->db->table('News');
		$builder->select('idNews, title, date, text, userName');
		$builder->join('User', 'User.idUser = News.idUser', 'left');
		$builder->orderBy('idNews', 'desc');
		if ($idnews != FALSE)
			$builder->where('idNews', $idnews);
		
		$query = $builder->get();
		return $query->getResultArray();
	}

	public function delete_news($id) {
		$builder = $this->db->table('News');
		return $builder->delete(['idNews' => $id]);
	}

	public function set_news($id, $title, $text, $idUser) {
		$builder = $this->db->table('News');
		$date = date("%Y-%m-%d %H:%i:%s", time()); //TODO la fonction mdate existe pu, mais si je comprends bien ça change rien parce qu'on y fournit des paramètres sécures
		
		if($id) {
			$data = array(
				'text' => $text,
				'title' => $title
			);
			$builder->where('idNews', $id);
			return $builder->update($data); 
		} else {
			$data = array(
				'text' => $text,
				'date' => $date,
				'title' => $title,
				'idUser' => $idUser
			);
			return $builder->insert($data);
		}
	}
}