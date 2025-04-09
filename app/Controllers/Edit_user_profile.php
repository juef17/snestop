<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Community_model;
use App\Models\Language_model;
use App\Models\User_model;

helper('utility_helper');

class Edit_user_profile extends Secure_controller {

	public function index() {
		$community_model = new Community_model();
		$language_model = new Language_model();
		$data = $this->getUserViewData();
		$this->setEditUserData($data);
		$data['communities'] = $community_model->get_communities_for_combobox();
		$data['languages'] = $language_model->get_languages();
		$data['view'] = 'edit_user_profile.php';
		echo view('template.php', $data);
	}

	private function setEditUserData(&$data) {
		$user = $_SESSION['loggedUser'];
		$data['edit_username'] = $user->userName;
		$data['edit_email'] = $user->email;
		$data['edit_language'] = $user->language;
		$data['edit_community'] = $user->idCommunity;
	}

	public function submit() {
		$data = $this->getUserViewData();
		$this->setEditUserData($data);
		
		//TODO
		//$this->setValidationRules();
		if(true/*$this->form_validation->run()*/) {
			$username = $this->request->getVar('edit_username');
			$password = $this->request->getVar('edit_password');
			$email = $this->request->getVar('edit_email');
			$language = $this->request->getVar('edit_language');
			$idCommunity = $this->request->getVar('edit_community');
			if($idCommunity == 0)
				$idCommunity = NULL;
			
			$data['view'] = 'message.php';
			$user_model = new User_model();
			if($user_model->updateUser($_SESSION['loggedUser']->idUser, $username, $password, $email, $language, $idCommunity))
				return redirect()->to(base_url() . "account/logout");
			else {
				$data['messageTitle'] = 'An error occured.';
				$data['message'] = 'Sorry, your profile can\'t be updated at this time. Please, try again later.';
			}
		} else {
			$community_model = new Community_model();
			$language_model = new Language_model();
			$data['communities'] = $community_model->get_communities_for_combobox();
			$data['languages'] = $language_model->get_languages();
			$data['view'] = 'edit_user_profile.php';
		}
		
		echo view('template.php', $data);
	}

	//AJAX POST
	public function setPlayerModes() {
		$loop = $this->request->getVar('loop');
		$randomize = $this->request->getVar('randomize') == 'true';
		
		if($data['success'] = $this->User_model->setPlayerModes($_SESSION['loggedUser']->idUser, $loop, $randomize))
		{
			$_SESSION['loggedUser']->loop = $loop;
			$_SESSION['loggedUser']->randomize = $randomize;
		}
		$data['message'] = 'An unexpected error occured, sorry :(';
		
		echo json_encode($data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		if($this->request->getVar('edit_username') != $_SESSION['loggedUser']->userName)
			$this->form_validation->set_rules('edit_username', 'Username', 'trim|required|xss_clean|alpha_dash|is_unique[User.userName]');

		if($this->request->getVar('edit_password') != '' || $this->request->getVar('edit_password_verif') != '')
			$this->form_validation->set_rules('edit_password', 'Password', 'trim|xss_clean|alpha_dash|matches[edit_password_verif]');

		if($this->request->getVar('edit_email') != $_SESSION['loggedUser']->email)
			$this->form_validation->set_rules('edit_email', 'Email', 'trim|required|xss_clean|valid_email|is_unique[User.email]');
		$this->form_validation->set_rules('edit_language', 'Language', 'trim|required|xss_clean');
		$this->form_validation->set_rules('edit_community', 'Community', 'trim|required|xss_clean|callback_validateCommunity');
		$this->form_validation->set_rules('edit_community_token', 'Community token', 'trim|xss_clean');
		//community?
	}

	public function validateCommunity($idCommunity) {
		if($idCommunity == 0) return true;
		$community_model = new Community_model();
		$this->form_validation->set_message('validateCommunity', 'Wrong token');
		$communityToken = $this->request->getVar('edit_community_token');
		$community = $community_model->get_Community($idCommunity);
		return $communityToken == $community->token;
	}
}
?>
