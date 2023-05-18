<?php
/**
 * Implement a search box web page that runs queries using Amazon
 * product search.
 * This page demonstrates using REST query that returns XML. Also the 
 * input search string is turned into an SEO friendly URL.
 */
class ProductsController extends Controller {
	private $messages = [];

	public function __construct( $parameters = null ) {

		// parse the query string to find search keywords
		// and redirect to an SEO-friendly URL with the keywords
		if (isset($_GET['keywords'])) {
			header('Location: /products/'.urlencode($_GET['keywords']));
			exit;
		}
		parent::__construct($parameters);
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
