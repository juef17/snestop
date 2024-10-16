<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Track_model;
use App\Models\Duel_result_model;
use App\Models\Shit_track_model;
use App\Models\Rating_personal_model;
use App\Models\Rating_community_model;

helper('utility_helper');

class Duelz extends Secure_controller
{
	public function index()
	{
		$data = $this->getUserViewData();
		$data['duelzMode'] = true;
		$data['view'] = 'duelz.php';
		$data['winape_enabled'] = false;
		
		echo view('template.php', $data);
	}

	//AJAX get
	public function getNewDuel() {
		$track_model = new Track_model();
		$shit_track_model = new Shit_track_model();
		$idTracks = $track_model->getIdTracksForDuel($_SESSION['loggedUser']->idUser);
		$trackInfo = array();
		// Chacun des deux idTrack avec son shitRatio correspondant
		for($i=0; $i<2; $i++) {
			$base64IdTrack = base64_encode($idTracks[$i]);
			$sanitizedIdTrack = rtrim($base64IdTrack, '='); // Equals signs in URL stopped working/started appearing in june 2020. No idea why but they should be safe to remove here.
			$encodedIdTrack = urlencode($sanitizedIdTrack);
			$trackInfo[$i] = array("idTrack" => $encodedIdTrack, "shitRatio" => urlencode(base64_encode($shit_track_model->get_Shit_Track_ratio_for_Track($idTracks[$i]))));
		}
		echo json_encode($trackInfo);
	}

	//AJAX get
	public function getSpc($idTrack) {
		$track_model = new Track_model();
		$idTrack = base64_decode(urldecode($idTrack));
		if($url = $track_model->get_Track_spc_url($idTrack)) {
			$filename = assets_dir() . 'spc/' . $url;
			$handle = fopen($filename, "rb");
			$contents = fread($handle, filesize($filename));
			fclose($handle);
			$a = substr($contents, 0, 46);
			$b = substr($contents, 46 + 64);
			$file = $a . str_repeat('0', 64) . $b;
			header("Cache-Control: no-cache private");
			header("Content-Transfer-Encoding: binary");
			header('Content-Length: '. strlen($file));
			echo $file;
		} else {
			echo "Could not find idTrack: " . $idTrack;
		}
	}

		//Ajax GET
	public function getTrack($idTrack) {
		$track_model = new Track_model();
		$idTrack = base64_decode(urldecode($idTrack));
		if($track = $track_model->get_Track($idTrack)) {
			$data['success'] = $track;
			$data['message'] = '';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'Can\t find track, sorry :(';
		}
		
		echo json_encode($data);
	}

	//AJAX get
	public function ping() {
		echo json_encode("Pongn'TTT!");
	}

	//AJAX get
	public function getNbDuelzTaken() {
		$duel_result_model = new Duel_result_model();
		$nb = $duel_result_model->get_number_of_duels_User($_SESSION['loggedUser']->idUser);
		echo json_encode($nb);
	}

	//AJAX post
	public function castVote() {
		$tracks = json_decode(json_encode($this->request->getVar('tracks'))); //Convert array to object. Slow but grabs me a nipple. // Maybe I'll grab more than just your nipple, Robin
		if($tracks) {
			$duel_result_model = new Duel_result_model();
			$track_model = new Track_model();
			$rating_personal_model = new Rating_personal_model();
			$rating_community_model = new Rating_community_model();
			$shit_track_model = new Shit_track_model();
			$idTrackA = base64_decode(urldecode($tracks->a->idTrack));
			$idTrackB = base64_decode(urldecode($tracks->b->idTrack));
			$idTrackWon = $tracks->a->winner == 'true' ? $idTrackA : $idTrackB;
			$idTrackLost = $tracks->a->winner == 'true' ? $idTrackB : $idTrackA;
			$data['success'] = $duel_result_model->new_Duel_Result($idTrackWon, $idTrackLost, $_SESSION['loggedUser']->idUser);
		error_log(json_encode($this->request->getVar('tracks')));
			$data['success'] = $track_model->update_ratings_Track($idTrackWon, $idTrackLost);
			$data['success'] = $rating_personal_model->update_ratings($idTrackWon, $idTrackLost, $_SESSION['loggedUser']->idUser);
			if($_SESSION['loggedUser']->idCommunity)
				$data['success'] = $rating_community_model->update_ratings($idTrackWon, $idTrackLost, $_SESSION['loggedUser']->idCommunity);
						
			if($tracks->a->shit == 'true')
				$data['success'] = $data['success'] && $shit_track_model->new_Shit_Track($_SESSION['loggedUser']->idUser, $idTrackA);
			
			if($tracks->b->shit == 'true')
				$data['success'] = $data['success'] && $shit_track_model->new_Shit_Track($_SESSION['loggedUser']->idUser, $idTrackB);
			
			$data['message'] = 'Good!';
		} else {
			$data['success'] = FALSE;
			$data['message'] = 'An unexpected error occured, sorry :(';
		}
		
		echo json_encode($data);
	}
}
