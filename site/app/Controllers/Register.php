<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Community_model;
use App\Models\Language_model;
use App\Models\User_model;

helper('utility_helper');

class Register extends Public_only_controller {

	public function index() {
		//TODO y'a pu de captcha dans CI4: ils l'ont enlevé parce qu'il était poche. Ils ont une classe honeypot qui fait un travail d'à-peu-près même qualité, sinon ils recommandent reCAPTCHA
		$community_model = new Community_model();
		$language_model = new Language_model();
		$data = $this->getUserViewData();
		$data['communities'] = $community_model->get_communities_for_combobox();
		$data['languages'] = $language_model->get_languages();
		$data['view'] = 'register.php';
		echo view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		
		//TODO
		//$this->setValidationRules();
		if(true/*$this->form_validation->run()*/) {
			$username = $this->request->getVar('reg_username');
			$password = $this->request->getVar('reg_password');
			$email = $this->request->getVar('reg_email');
			$language = $this->request->getVar('reg_language');
			$registerToken = generate_random_string();
			$idCommunity = $this->request->getVar('reg_community');
			if($idCommunity == 0)
				$idCommunity = NULL;
			
			$user_model = new User_model();
			$user_model->addUser($username, $password, $email, $language, $registerToken, $idCommunity);
			$this->sendConfirmationEmail($username, $email, $registerToken);

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'One final step...';
			$data['message'] = 'Thank you for registering! Please confirm your email address using the message you will receive shortly.';
		} else {
			$community_model = new Community_model();
			$language_model = new Language_model();
			$data['communities'] = $community_model->get_communities_for_combobox();
			$data['languages'] = $language_model->get_languages();
			$data['view'] = 'register.php';
		}
		
		echo view('template.php', $data);
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('reg_username', 'Username', 'trim|required|xss_clean|alpha_dash|is_unique[User.userName]');
		$this->form_validation->set_rules('reg_password', 'Password', 'trim|required|xss_clean|alpha_dash|matches[reg_password_verif]');
		$this->form_validation->set_rules('reg_email', 'Email', 'trim|required|xss_clean|valid_email|is_unique[User.email]');
		$this->form_validation->set_rules('reg_language', 'Language', 'trim|required|xss_clean');
		$this->form_validation->set_rules('reg_captcha', 'Captcha', 'trim|required|xss_clean|callback_validateCaptcha');
		$this->form_validation->set_rules('reg_calc', 'Math question', 'trim|required|xss_clean|integer|callback_validateMathQuestion');
		$this->form_validation->set_rules('reg_community', 'Community', 'trim|required|xss_clean|callback_validateCommunity');
		$this->form_validation->set_rules('reg_community_token', 'Community token', 'trim|xss_clean');
		//community?
	}

	public function validateMathQuestion($calc) {
		$this->form_validation->set_message('validateMathQuestion', 'Wrong answer to the math question.');
		return intval($calc) === 3600;
	}

	private function sendConfirmationEmail($username, $emailAddress, $token) {
		$email = \Config\Services::email();
		$config['newline'] = "\r\n";
		$email->initialize($config);
		$email->setFrom('donotreply@snestop.com', 'Top SNES Tracks Project');
		$email->setTo($emailAddress);
		$email->setSubject('Registration confirmation');
		$email->setMessage($this->getFormattedEmailContent($username, $token));
		
		//TODO vérifier si ça marche, mon serveur est pas configuré pour pouvoir envoyer des emails
		if(!$email->send(false))
			die($email->printDebugger());
	}

	public function getFormattedEmailContent($username, $token) {
		$content = file_get_contents(emails_dir() . 'register.html');
		$content = str_replace('{username}', $username, $content);
		$content = str_replace('{token}', $token, $content);
		$content = str_replace('{baseurl}', base_url(), $content);
		return $content;
	}

	public function validateCommunity($idCommunity) {
		if($idCommunity == 0) return true;
		$community_model = new Community_model();
		$this->form_validation->set_message('validateCommunity', 'Wrong token');
		$communityToken = $this->request->getVar('reg_community_token');
		$community = $this->Community_model->get_Community($idCommunity);
		return $communityToken == $community->token;
	}
}
