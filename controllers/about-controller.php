<?php
class AboutController extends Controller {

	public function run() {
		$v = new AboutView();
		$v->run();
	}
}
