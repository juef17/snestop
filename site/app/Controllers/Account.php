<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User_model;

helper('cookie');
helper('utility_helper');

class Account extends Public_controller
{

	public function login()
	{
		helper(['form', 'url']);
		
		//TODO avait on avait xss_clean comme règle pour le user et le pass, mais c'est deprecated
		if ($this->request->getMethod() === 'post' && $this->validate([
			'username' => 'trim|required',
			'password'  => 'trim|required'
			])
			&& $this->check_database())
		{
			// Success
			if($this->request->getVar('rememberme'))
				$this->setCookie();
		}
		else
		{
			// Failed!
			session_unset(); // TODO à quoi ça sert? J'veux dire, si on essaie de se logger, c'est qu'on est déjà pas loggé, non?
		}

		if(isset($_GET['returnUrl'])) {
			$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
			return redirect()->to($protocol . $_GET['returnUrl']);
		} else
			return redirect()->to(base_url());
	}

	private function setCookie() {
		//TODO: Cookie “snestop” will be soon rejected because it has the “sameSite” attribute set to “none” or an invalid value, without the “secure” attribute. To know more about the “sameSite“ attribute, read https://developer.mozilla.org/docs/Web/HTTP/Headers/Set-Cookie/SameSite
		$user_model = new User_model();
		$token = generate_random_string();
		$cookie = [
			'name'   => 'rememberMeSnestopToken',
			'value'  => $token,
			'expire' => '157680000'  // 5 years
		];
		set_cookie($cookie);
		$user_model->set_token($this->request->getVar('username'), $token);
	}

	private function deleteCookie() {
		$user_model = new User_model();
		$cookie = [
			'name' => 'rememberMeSnestopToken',
			'value' => '',
			'expire' => '0'
		];
		set_cookie($cookie);
		$username = $_SESSION['loggedUser']->userName;
		$user_model->set_token($username, NULL);
	}

	public function logout() {
		$this->deleteCookie();
		session_unset();
		return redirect()->to(base_url());
	}

	public function check_database() {
		$username = $this->request->getVar('username');
		$password = $this->request->getVar('password');

		$user_model = new User_model();
		$user = $user_model->login($username, $password);
		// TODO
		/*if(!$user) {
			$this->erreurLogin = 'Invalid username or password';
			return FALSE;
		} else if(!$user->enabled) {
			$this->erreurLogin = 'Sorry, your account has been disabled.';
			return FALSE;
		} else if($user->registrationToken != NULL) {
			$this->erreurLogin = 'You must confirm your<br />email address first!';
			return FALSE;
		} else {*/
		if($user)
		{
			$_SESSION['loggedUser'] = $user;
			return TRUE;
		}
	}
}
