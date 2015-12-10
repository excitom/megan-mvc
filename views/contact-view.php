<?php

class ContactView extends View {
	public function __construct( $title = 'Contact Me' ) {
		$this->setNavBarActive('contact');
		parent::__construct( $title );
	}

	/**
	 * Generate the main section 
	 */
	public function getMainSection() {
		return <<<HTML
<div class="container theme-showcase" role="main">
  <div class="jumbotron">
	<h2>Contact Me</h2>
	<p>
	This is a demonstration framework that I built.
	The PHP code is my own. 
	The CSS is the Twitter Bootstrap theme with minimal customization
	</p>
  </div>
</div>
HTML;
	}
}
