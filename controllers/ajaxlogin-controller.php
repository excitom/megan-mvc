<?php
class AjaxLoginController extends Controller {

	private $nickName;
	private $password;
	private $firstName;
	private $lastName;
	private $id;

	/**
	 * Process an AJAX call from the login form
	 * Input:
	 *  user name, password
	 * Output:
	 *  HTTP status code, completion message, user name and session
	 *  cookies set.
	 */
	public function run() {
		if ($this->validInput()) {
			if ($this->loginUser()) {
				Cookies::setLoginCookies($this->nickName, $this->id, $this->firstName, $this->lastName);
				print 'Success!';
			}
		}
	}

	/**
	 * The front end javascript validates input, but it's a
	 * good practice to never trust that this happened.
	 */
	private function validInput() {
		$this->nickName = trim($_REQUEST['nn']);
		if (empty($this->nickName)) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Missing user name';
			return false;
		}
		$this->password = trim($_REQUEST['pw']);
		if (empty($this->password)) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Missing password';
			return false;
		}

		// validation tests passed
		return true;
	}

	/**
	 * Validate the user name and password
	 */
	private function loginUser() {
		$u = new UsersDb();
		$user = $u->getUserByName( $this->nickName );
		$user = array_pop($user);
		if ($user === false) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'User name not registered';
			return false;
		}

		$pwdPeppered = hash_hmac("sha256", $this->password, $_SERVER['PEPPER']);
		if (password_verify($pwdPeppered, $user['password']) === false) {

			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Login failed';
			return false;
		}

		$this->id = $user['id'];
		$this->firstName = $user['firstName'];
		$this->lastName = $user['lastName'];

		return true;
	}
}
