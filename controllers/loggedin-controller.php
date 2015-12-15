<?php
class LoggedInController extends Controller {

	protected $userid;

	public function __construct() {
		$this->userid = Cookies::isLoggedIn();
		if ($this->userid === false) {
			header('Location: /login');
			exit;
		}
	}
}
