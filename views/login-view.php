<?php

class LoginView extends View {
	public function __construct( $title = 'Megan PHP Framework - Login' ) {
		$this->setNavBarActive('home');
		parent::__construct( $title );
	}

	/**
	 * Generate the middle section of the home page
	 */
	protected function getMainSection() {

		$nickName = (empty($_COOKIE['n'])) ? '' : $_COOKIE['n'];
		$form = Forms::getLoginForm($nickName);
		$js = Forms::getLoginFormJs();
		$this->addInlineJs($js);

		return <<<HTML
<div class="container theme-showcase" role="main">
  <div class="jumbotron">
	<h3>Sign In</h3>
$form
  </div>
</div>
HTML;
	}
}
