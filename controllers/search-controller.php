<?php
class SearchController extends Controller {
	private $parameters;

	public function __construct( $parameters ) {
		$this->parameters = $parameters;
	}

	public function run() {
		$results = '';
		if (!empty($this->parameters)) {
			# implement search function
		}

		$v = new SearchView();
		$v->run( $results );
	}
}
