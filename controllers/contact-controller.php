<?php
class ContactController extends Controller {

	public function run() {
		$v = new ContactView();
		$v->run();
	}
}
