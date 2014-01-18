<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends Public_Only_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('captcha');
		$this->load->model('Community_model','',TRUE);
		$this->load->model('Language_model','',TRUE);
	}

	public function index() {
		$data = $this->getUserViewData();
		$data['captcha'] = $this->initCaptcha();
		$data['communities'] = $this->Community_model->get_communities_for_combobox();
		$data['languages'] = $this->Language_model->get_languages();
		$data['view'] = 'register.php';
		$this->load->view('template.php', $data);
	}

	public function submit() {
		$data = $this->getUserViewData();
		
		$this->setValidationRules();
		if($this->form_validation->run()) {
			unset($_SESSION['captcha']);
			unset($_SESSION['nbcaptcharetry']);

			$username = $this->input->post('reg_username');
			$password = $this->input->post('reg_password');
			$email = $this->input->post('reg_email');
			$language = $this->input->post('reg_language');
			$registerToken = generate_random_string();
			$idCommunity = $this->input->post('reg_community');
			if($idCommunity == 0)
				$idCommunity = NULL;
			
			$this->User_model->addUser($username, $password, $email, $language, $registerToken, $idCommunity);
			$this->sendConfirmationEmail($username, $email, $registerToken);

			$data['view'] = 'message.php';
			$data['messageTitle'] = 'One final step...';
			$data['message'] = 'Thank you for registering! Please confirm your email address using the message you will receive shortly.';
		} else {
			$data['captcha'] = $this->initCaptcha();
			$data['communities'] = $this->Community_model->get_communities_for_combobox();
			$data['languages'] = $this->Language_model->get_languages();
			$data['view'] = 'register.php';
		}
		
		$this->load->view('template.php', $data);
	}

	//AJAX
	public function reloadc() {
		echo $this->initCaptcha();
	}

	private function setValidationRules() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('reg_username', 'Username', 'trim|required|xss_clean|alpha_dash|is_unique[User.userName]');
		$this->form_validation->set_rules('reg_password', 'Password', 'trim|required|xss_clean|alpha_dash|callback_verifyPassword');
		$this->form_validation->set_rules('reg_email', 'Email', 'trim|required|xss_clean|valid_email|is_unique[User.email]');
		$this->form_validation->set_rules('reg_language', 'Language', 'trim|required|xss_clean');
		$this->form_validation->set_rules('reg_captcha', 'Captcha', 'trim|required|xss_clean|callback_validateCaptcha');
		$this->form_validation->set_rules('reg_community', 'Community', 'trim|required|xss_clean|callback_validateCommunity');
		$this->form_validation->set_rules('reg_community_token', 'Community token', 'trim|xss_clean');
		//community?
	}

	public function verifyPassword($password) {
		$this->form_validation->set_message('verifyPassword', 'Passwords fields don\'t match');
		return $password == $this->input->post('reg_password_verif');
	}

	private function sendConfirmationEmail($username, $email, $token) {
		$this->load->library('email');
		$this->email->from('donotreply@snestop.com', 'Top SNES Tracks Project');
		$this->email->to($email);
		$this->email->subject('Registration confirmation');
		$this->email->message($this->getFormattedEmailContent($username, $token));
		$this->email->set_newline("\r\n");
		
		if(!$this->email->send())
			die($this->email->print_debugger());
	}

	public function getFormattedEmailContent($username, $token) {
		$content = file_get_contents(emails_dir() . 'register.html');
		$content = str_replace('{username}', $username, $content);
		$content = str_replace('{token}', $token, $content);
		$content = str_replace('{baseurl}', base_url(), $content);
		return $content;
	}

	public function validateCaptcha($captcha) {
		if(! $retval = strtoupper($captcha) === strtoupper($_SESSION['captcha']))
			$this->form_validation->set_message('validateCaptcha', 'Try again');
		else
			$_SESSION['nbcaptcharetry']--;
		
		return $retval;
	}

	public function validateCommunity($idCommunity) {
		if($idCommunity == 0) return true;
		$this->form_validation->set_message('validateCommunity', 'Wrong token');
		$communityToken = $this->input->post('reg_community_token');
		$community = $this->Community_model->get_Community($idCommunity);
		return $communityToken == $community->token;
	}

	private function initCaptcha() {
		if(!isset($_SESSION['nbcaptcharetry']))
			$_SESSION['nbcaptcharetry'] = 1;
		else
			$_SESSION['nbcaptcharetry']++;

		if($_SESSION['nbcaptcharetry'] > 5) {
			unset($_SESSION['captcha']);
			return '<p>Sorry, try again in two hours.</p>';
		} else {
			$_SESSION['captcha'] = generate_random_string(6);
			
			$vals = array(
				'word' => $_SESSION['captcha'],
				'img_path' => assets_dir() . 'images/captcha/',
				'img_url' => asset_url() . 'images/captcha/',
				'font_path' => assets_dir() . 'fonts/snes.ttf',
				'img_width' => '240',
				'img_height' => 30,
				'expiration' => 120
				);
			
			return '<p>Attempt ' . $_SESSION['nbcaptcharetry'] . ' of 5</p>' .
				create_captcha($vals)['image'];
		}
	}
}
?>
