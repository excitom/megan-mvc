<?php

class RegisterView extends View {
	public function __construct( $title = 'Megan PHP Framework - Registration' ) {
		parent::__construct( $title );
	}

	/**
	 * Generate the middle section of the home page, without
	 * the top margin.
	 */
	protected function getBodySection() {
		$mainSection = $this->getMainSection();
		$footer = $this->getFooter();

		return <<<HTML
<body role="document">
$mainSection
$footer
</body>
HTML;
	}

	protected function getMainSection() {

		$form = Forms::getRegisterForm();
		$js = Forms::getRegisterJs( '/' );
		$this->addInlineJs($js);

		return <<<HTML
<div class="container theme-showcase" role="main">
  <div class="jumbotron">
	<h3>Register</h3>
$form
  </div>
</div>
HTML;
	}
}
