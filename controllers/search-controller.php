<?php
/**
 * Implement a search box web page that runs queries on Google and/or Bing.
 * This page demonstrates using REST queries in parallel (see the
 * Search Model).
 */
class SearchController extends Controller {
	protected $parameters = '';
	private $engines = array();
	private $messages = array();

	public function __construct( $parameters ) {
		// parse the query string to find search keywords
		// and also the choice of search engine(s)
		if (isset($_GET['keywords'])) {
			$this->parameters = $_GET['keywords'];
		}
		if (isset($_GET['useGoogle'])) {
			$this->engines['g'] = true;
		}
		if (isset($_GET['useBing'])) {
			$this->engines['b'] = true;
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

		$v = new SearchView();
		$v->setMessages( $this->messages );
		$v->run( $results );
	}

	private function doSearch() {
		if (empty($this->engines)) {
			$this->messages[] = 'Please choose either, or both, search engines';
			return '';
		}

		$m = new SearchModel( $this->engines );
		return $m->run($this->parameters);
	}
}
