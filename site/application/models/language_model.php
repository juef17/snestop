<?php
class Language_model extends CI_Model {
	public function get_languages() {
		$retval = array();
		$retval[] = 'English';
		$retval[] = 'French';
		$retval[] = 'German';

		asort($retval);
		return $retval;
	}
}
