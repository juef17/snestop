<?php
class News_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}

	public function get_news($idnews = FALSE) {
		$this->db->select('idNews, title, date, text, userName');
		$this->db->from('News');
		$this->db->join('User', 'User.idUser = News.idUser', 'left');
		$this->db->order_by('idNews', 'desc');
		if ($idnews != FALSE)
			$this->db->where('idNews', $idnews);
		
		$query = $this->db->get();
		return $query->result_array();
	}

	public function delete_news($id) {
		return $this->db->delete('News', array('idNews' => $id));
	}

	public function set_news($id, $title, $text, $idUser) {
		$date = mdate("%Y-%m-%d %H:%i:%s", time());
		
		if($id) {
			$data = array(
				'text' => $text,
				'title' => $title
			);
			$this->db->where('idNews', $id);
			return $this->db->update('News', $data); 
		} else {
			$data = array(
				'text' => $text,
				'date' => $date,
				'title' => $title,
				'idUser' => $idUser
			);
			return $this->db->insert('News', $data);
		}
	}
}
