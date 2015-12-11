<?php
class LoginController extends Controller {
	public function run() {
		$v = new LoginView();
		$v->run();
	}
}
