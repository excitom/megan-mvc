<?php
class RegisterController extends Controller {
	public function run() {
		$v = new RegisterView();
		$v->run();
	}
}
