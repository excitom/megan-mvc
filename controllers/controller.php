<?php
class Controller {
	public function __construct() {
	}

	public function run() {
		$v = new View();
		$v->run();
	}
}
