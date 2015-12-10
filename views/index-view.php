<?php

class IndexView extends View {
	public function __construct( $title = 'This is the home page' ) {
		$this->setNavBarActive('home');
		parent::__construct( $title );
	}

	/**
	 * Generate the middle section of the home page
	 */
	protected function getMainSection() {
		return <<<HTML
<div class="container theme-showcase" role="main">
  <div class="jumbotron">
	<h1>Megan PHP Framework</h1>
	<p>
	This is a demonstration framework that I built.
	The PHP code is my own. 
	The CSS is the Twitter Bootstrap theme with minimal customization
	</p>
	<p>
	Megan is the name of my youngest daughter.
	</p>
  </div>
</div>
HTML;
	}
}
