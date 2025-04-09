<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Review_model;

helper('utility_helper');

class Reviews_dashboard extends Admin_controller {

	public function index($unapprovedOnly = FALSE)
	{
		$review_model = new Review_model();
		$data = $this->getUserViewData();
		$data['reviews'] = $review_model->get_Review($unapprovedOnly);
		$data['view'] = 'reviews_dashboard.php';
		$data['unapprovedOnly'] = $unapprovedOnly;
		echo view('template.php', $data);
	}

	public function deleteReview() {
		$review_model = new Review_model();
		if(($idUser = $this->request->getVar('idUser', FILTER_SANITIZE_NUMBER_INT)) && ($idTrack = $this->request->getVar('idTrack', FILTER_SANITIZE_NUMBER_INT)))
			$review_model->delete_Review($idUser, $idTrack);
		
		return redirect()->to(base_url() . "reviews_dashboard");
	}

	public function approveReview() {
		$review_model = new Review_model();
		if(($idUser = $this->request->getVar('idUser', FILTER_SANITIZE_NUMBER_INT)) && ($idTrack = $this->request->getVar('idTrack', FILTER_SANITIZE_NUMBER_INT)))
			$review_model->approve_Review($idUser, $idTrack);
		
		return redirect()->to(base_url() . "reviews_dashboard");
	}

	//Ajax POST
	public function editReview() {
		$review_model = new Review_model();
		if(($idUser = $this->request->getVar('idUser', FILTER_SANITIZE_NUMBER_INT))
			&& ($idTrack = $this->request->getVar('idTrack', FILTER_SANITIZE_NUMBER_INT))
			&& ($text = $this->request->getVar('text', FILTER_SANITIZE_STRING))
			&& ($review_model->update_Review($idUser, $idTrack, $text)))
		{
			echo json_encode(array('success' => FILTER_SANITIZE_STRING));
		} else {
			echo json_encode(array('success' => false, 'message' => 'An error occured.'));
		}
	}

	//Ajax GET
	public function getReviewForEdit($idUser, $idTrack) {
		$review_model = new Review_model();
		$review = $review_model->get_Review(false, $idUser, $idTrack);
		echo json_encode($review);
	}
}
