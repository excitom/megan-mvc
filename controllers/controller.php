<?php
class Controller {
	protected $parameters;

	public function __construct( $parameters = null) {
		$this->parameters = $parameters;
	}

	public function run() {
		$v = new View();
		$v->run();
	}
}
