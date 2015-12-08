<?php
/**
 * Implement a search box web page that runs queries using Amazon
 * product search.
 * This page demonstrates using REST query that returns XML. Also the 
 * input search string is turned into an SEO friendly URL.
 */
class ProductsController extends Controller {
	private $parameters = '';
	private $messages = array();

	public function __construct( $parameters ) {
		// parse the query string to find search keywords
		// and also the choice of search engine(s)
		if (isset($_GET['keywords'])) {
			$this->parameters = $_GET['keywords'];
		}

		// keywords may also be passed in as part of the URL
		if (!empty($parameters)) {
			if (!empty($this->parameters)) {
				$this->parameters = $parameters . ' ' . $this->parameters;
			} else {
				$this->parameters = $parameters;
			}
		}
	}

	public function run( $results = '' ) {
		if (!empty($this->parameters)) {
			$results = $this->doSearch();
		}

		$v = new ProductsView();
		$v->setMessages( $this->messages );
		$v->run( $results );
	}

	private function doSearch() {
		$m = new ProductsModel();
		return $m->run($this->parameters);
	}
}
