<?php
/**
 * Show/edit information about the loggedin user
 */
class MyAccountController extends Controller {
	private $messages = [];
	protected $parameters = [];
	protected $nickName = '';

	public function __construct( $parameters = null ) {
		$this->parameters = $_POST;
		$user = Cookies::getUserInfo();
		$this->nickName = $user[0];
	}

	public function run( $results = '' ) {
		$m = new MyAccountModel();
		$userInfo = $m->getUserInfo($this->nickName);
		if (isset($this->parameters['cmd'])) {
			$valid = 1;
			if (!filter_var($this->parameters['email'], FILTER_VALIDATE_EMAIL)) {
				$valid = 0;
				$this->messages[] = 'Invalid email address';
			}
			$userInfo['email'] = $this->parameters['email'];
			$userInfo['firstName'] = $this->parameters['firstName'];
			$userInfo['lastName'] = $this->parameters['lastName'];
			if ($valid) {
				$m->updateUserInfo($userInfo);
			}
		}

		$v = new MyAccountView();
		$v->setMessages( $this->messages );
		$v->run( $userInfo );
	}
}
