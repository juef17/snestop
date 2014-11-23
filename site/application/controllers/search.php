<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Public_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('User_model','',TRUE);
		//$this->load->model('Game_model','',TRUE);
		//$this->load->model('Track_model','',TRUE);
	}

	public function index($searchTarget, $searchString) {
		$data = $this->getUserViewData();
		
		$data['searchString'] = rawurldecode($searchString);
		
		if($searchTarget == '2') {
			$data['users'] = $this->User_model->search($searchString);
			$data['view'] = 'search_results_user.php';
		}
		
		$this->load->view('template.php', $data);
	}
}
?>
