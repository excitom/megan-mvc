<?php
class AjaxRegisterController extends Controller {

	public function run() {
		if (false) {
			header($_SERVER["SERVER_PROTOCOL"]." 400 Failed");
			print 'Duplicate name';
		} else {
			print 'Success!';
		}
	}
}
