<?php
/**
 * Show/edit information about the loggedin user
 */
class MyAccountController extends Controller {
	private $messages = array();

	public function __construct( $parameters = null ) {
		parent::__construct($parameters);
	}

	public function run( $results = '' ) {
		if (!empty($this->parameters)) {
		}

		$v = new MyAccountView();
		$v->setMessages( $this->messages );
		$v->run( $results );
	}
}
