<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Duelz extends Secure_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Track_model','',TRUE);
		$this->load->model('Duel_Result_model','',TRUE);
		$this->load->model('Shit_Track_model','',TRUE);
		$this->load->model('Rating_Personal_model','',TRUE);
		$this->load->model('Rating_Community_model','',TRUE);
	}

	public function index()
	{
		$data = $this->getUserViewData();
		$data['view'] = 'duelz.php';
		$data['winape_enabled'] = false;
		
		$this->load->view('template.php', $data);
	}

	//AJAX get
	public function getNewDuel() {
		$idTracks = $this->Track_model->getIdTracksForDuel($_SESSION['loggedUser']->idUser);
		echo json_encode($idTracks);
	}

	//Ajax GET
	public function getNbDuelzTaken() {
		$nb = $this->Duel_Result_model->get_number_of_duels_User($_SESSION['loggedUser']->idUser);
		echo json_encode($nb);
	}

	//AJAX post
	public function castVote() {
		$tracks = json_decode(json_encode($this->input->post('tracks'))); //Convert array to object. Slow but grabs me a nipple.
		
		if($tracks) {
			$idTrackWon = $tracks->a->winner == 'true' ? $tracks->a->idTrack : $tracks->b->idTrack;
			$idTrackLost = $tracks->a->winner == 'true' ? $tracks->b->idTrack : $tracks->a->idTrack;
			$data['success'] = $this->Duel_Result_model->new_Duel_Result($idTrackWon, $idTrackLost, $_SESSION['loggedUser']->idUser);
			$data['success'] = $this->Track_model->update_ratings_Track($idTrackWon, $idTrackLost);
			$data['success'] = $this->Rating_Personal_model->update_ratings($idTrackWon, $idTrackLost, $_SESSION['loggedUser']->idUser);
			if($_SESSION['loggedUser']->idCommunity)
				$data['success'] = $this->Rating_Community_model->update_ratings($idTrackWon, $idTrackLost, $_SESSION['loggedUser']->idCommunity);
						
			if($tracks->a->shit == 'true')
				$data['success'] = $data['success'] && $this->Shit_Track_model->new_Shit_Track($_SESSION['loggedUser']->idUser, $tracks->a->idTrack);
			
			if($tracks->b->shit == 'true')
				$data['success'] = $data['success'] && $this->Shit_Track_model->new_Shit_Track($_SESSION['loggedUser']->idUser, $tracks->b->idTrack);
			
			$data['message'] = 'An unexpected error occured, sorry :(';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'An unexpected error occured, sorry :(';
		}
		
		echo json_encode($data);
	}
}
