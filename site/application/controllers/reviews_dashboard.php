<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reviews_Dashboard extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Review_model','',TRUE);
		$this->load->model('Track_model','',TRUE);
	}

	public function index($unapprovedOnly = FALSE)
	{
		$data = $this->getUserViewData();
		$data['reviews'] = $this->Review_model->get_Review($unapprovedOnly);
		$data['view'] = 'reviews_dashboard.php';
		$data['unapprovedOnly'] = $unapprovedOnly;
		$this->load->view('template.php', $data);
	}

	public function deleteReview() {
		if(($idUser = $this->input->post('idUser', TRUE)) && ($idTrack = $this->input->post('idTrack', TRUE)))
			$this->Review_model->delete_Review($idUser, $idTrack);
		
		redirect('/reviews_dashboard');
	}

	public function approveReview() {
		if(($idUser = $this->input->post('idUser', TRUE)) && ($idTrack = $this->input->post('idTrack', TRUE)))
			$this->Review_model->approve_Review($idUser, $idTrack);
		
		redirect('/reviews_dashboard');
	}

	//Ajax POST
	public function editReview() {
		if(($idUser = $this->input->post('idUser', TRUE))
			&& ($idTrack = $this->input->post('idTrack', TRUE))
			&& ($text = $this->input->post('text', TRUE))
			&& ($this->Review_model->update_Review($idUser, $idTrack, $text)))
		{
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('success' => false, 'message' => 'An error occured.'));
		}
	}

	//Ajax GET
	public function getReviewForEdit($idUser, $idTrack) {
		$review = $this->Review_model->get_Review(false, $idUser, $idTrack);
		echo json_encode($review);
	}
}
