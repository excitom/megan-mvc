<?php
/**
 * Implement a search box web page that runs accepts a street address
 * that will be displayed on a Google map
 */
class MapController extends Controller {
	private $messages = array();

	public function run( $results = '' ) {
		// parse the query string to find search keywords
		// and also the choice of search engine(s)
		if (isset($_GET['address'])) {
			$this->parameters = $_GET['address'];
		}
		if (!empty($this->parameters)) {
			$results = $this->doSearch();
		}

		$v = new MapView();
		$v->setMessages( $this->messages );
		$v->run( $results );
	}

	private function doSearch() {
		$m = new MapModel();
		return $m->run($this->parameters);
	}
}
