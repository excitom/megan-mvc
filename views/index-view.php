<?php

class IndexView extends View {
	public function __construct( $title = 'This is the home page' ) {
		$this->setNavBarActive('home');
		parent::__construct( $title );
	}

	#public function run() {
	#	The base View class handles everything
	#}
}
