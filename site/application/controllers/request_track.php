<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Track extends CI_Controller {
	public function index()
	{
		$data['view'] = 'request_track.php';
		$this->load->view('template.php', $data);
	}
}
