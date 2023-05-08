<?php
class AjaxRegisterController extends Controller {

	private $nickName;
	private $email;
	private $password;
	private $firstName;
	private $lastName;
	private $uid;
	private $id;

	/**
	 * Process an AJAX call from the registration modal window.
	 * Input:
	 *  user name, password, email, optional first and last name
	 * Output:
	 *  HTTP status code, completion message, user name and session
	 *  cookies set.
	 */
	public function run() {
		if ($this->validInput()) {
			if ($this->addUser()) {
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
		$this->nickName = trim($_REQUEST['nickName']);
		if (empty($this->nickName)) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Missing user name';
			return false;
		}
		if (preg_match("/^[A-Za-z][A-Za-z0-9_-]+$/", $this->nickName) === 0) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Invalid user name';
			return false;
		}
		$this->email = trim($_REQUEST['email']);
		if (empty($this->email)) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Missing email';
			return false;
		}
		if (preg_match("/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/", $this->email) === 0) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Invalid email';
			return false;
		}
		// Note: no rules are enforced for password strength, this 
		// is just a simple demo.
		$this->password = trim($_REQUEST['password']);
		if (empty($this->password)) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Missing password';
			return false;
		}
		// XSS note: the Db objects always use prepared statements and this
		// protects against malicious input. The nickName and email have
		// already been tested against regular expression patterns that 
		// screen out malicious characters. The first name and last name are
		// used for display purposes so we run them through the 'htmlentities'
		// filter to make them safer to display. Note however that this will
		// be a problem if we try to search the DB on the first or last name
		// columns and some of the characters have been converted.
		//
		// The password is never displayed and special characters are allowed
		// in it.
		$this->firstName = htmlentities(trim($_REQUEST['firstName']));
		$this->lastName = htmlentities(trim($_REQUEST['lastName']));

		// The uid cookie is something we set so we shouldn't have to 
		// worry about it. Using htmlentities is extra paranoia.
		$this->uid = htmlentities(trim($_COOKIE['u']));

		// validation tests passed
		return true;
	}

	/**
	 * Add a new user to the database
	 */
	private function addUser() {
		$u = new UsersDb();
		if ($u->getUserByName( $this->nickName ) !== false) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Duplicate user name';
			return false;
		}
		if ($u->getUserByEmail( $this->email ) !== false) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Duplicate email';
			return false;
		}

		// One-way encrypt the password
		$this->password = password_hash( $this->password, PASSWORD_DEFAULT);

		// Note: There is a very small race condition if two people
		// simultaneously try to register the name name or email, but if
		// that happens the only result is a less specific error message
		// for the loser.
		$this->id = $u->addUser( $this->nickName, $this->password, $this->email, $this->firstName, $this->lastName, $this->uid );
		if ($this->id === false) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Registration failed';
			return false;
		}
		return true;
	}
}
