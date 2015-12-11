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
	To Do: put in a contact form
	</p>
  </div>
</div>
HTML;
	}
}
