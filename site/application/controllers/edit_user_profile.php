<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit_User_Profile extends Secure_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Community_model','',TRUE);
		$this->load->model('Language_model','',TRUE);
	}

	public function index() {
		$data = $this->getUserViewData();
		$this->setEditUserData($data);
		$data['communities'] = $this->Community_model->get_communities_for_combobox();
		$data['languages'] = $this->Language_model->get_languages();
		$data['view'] = 'edit_user_profile.php';
		$this->load->view('template.php', $data);
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
		
		$this->setValidationRules();
		if($this->form_validation->run()) {
			$username = $this->input->post('edit_username');
			$password = $this->input->post('edit_password');
			$email = $this->input->post('edit_email');
			$language = $this->input->post('edit_language');
			$idCommunity = $this->input->post('edit_community');
			if($idCommunity == 0)
				$idCommunity = NULL;
			
			$data['view'] = 'message.php';
			if($this->User_model->updateUser($_SESSION['loggedUser']->idUser, $username, $password, $email, $language, $idCommunity)) {
				redirect('/account/logout');
				return;
			} else {
				$data['messageTitle'] = 'An error occured.';
				$data['message'] = 'Sorry, your profile can\'t be updated at this time. Please, try again later.';
			}
		} else {
			$data['communities'] = $this->Community_model->get_communities_for_combobox();
			$data['languages'] = $this->Language_model->get_languages();
			$data['view'] = 'edit_user_profile.php';
		}
		
		$this->load->view('template.php', $data);
	}

	//AJAX POST
	public function setPlayerModes() {
		$loop = $this->input->post('loop') == 'true';
		$randomize = $this->input->post('randomize') == 'true';
		
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
		if($this->input->post('edit_username') != $_SESSION['loggedUser']->userName)
			$this->form_validation->set_rules('edit_username', 'Username', 'trim|required|xss_clean|alpha_dash|is_unique[User.userName]');

		if($this->input->post('edit_password') != '' || $this->input->post('edit_password_verif') != '')
			$this->form_validation->set_rules('edit_password', 'Password', 'trim|xss_clean|alpha_dash|callback_verifyPassword');

		if($this->input->post('edit_email') != $_SESSION['loggedUser']->email)
			$this->form_validation->set_rules('edit_email', 'Email', 'trim|required|xss_clean|valid_email|is_unique[User.email]');
		$this->form_validation->set_rules('edit_language', 'Language', 'trim|required|xss_clean');
		$this->form_validation->set_rules('edit_community', 'Community', 'trim|required|xss_clean|callback_validateCommunity');
		$this->form_validation->set_rules('edit_community_token', 'Community token', 'trim|xss_clean');
		//community?
	}

	public function verifyPassword($password) {
		$this->form_validation->set_message('verifyPassword', 'Passwords fields don\'t match');
		return $password == $this->input->post('edit_password_verif');
	}

	public function validateCommunity($idCommunity) {
		if($idCommunity == 0) return true;
		$this->form_validation->set_message('validateCommunity', 'Wrong token');
		$communityToken = $this->input->post('edit_community_token');
		$community = $this->Community_model->get_Community($idCommunity);
		return $communityToken == $community->token;
	}
}
?>
