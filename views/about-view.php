<?php

class AboutView extends View {
	public function __construct( $title = 'About the Megan Framework' ) {
		$this->setNavBarActive('about');
		parent::__construct( $title );
	}

	/**
	 * Generate the main section
	 */
	public function getMainSection() {
		return <<<HTML
<div class="container theme-showcase" role="main">
  <div class="jumbotron">
	<h2>About Me</h2>
	<p>
	I have been writing code since long before the Internet was a thing.
	</p>
	<p>
	You can find the source code for this website <a href="https://github.com/excitom/megan-mvc">on GitHub</a>
	</p>
	<p>
	You can read more about me on <a href="https://www.linkedin.com/in/tom-lang-03911a">LinkedIn</a>.
	</p>
	<p>
	This is the <a href="http://ishmail.com">first web page</a> I ever built, in 1994. I keep it around for sentimental reasons.
	</p>
  </div>
</div>
HTML;
	}
}
