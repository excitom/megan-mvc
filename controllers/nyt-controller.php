<?php
/**
 * Implement a search page that fetches the current top articles
 * from a section of the New York Times web site.
 */
class NytController extends Controller {
	private $section = '';

	public function __construct( $parameters = null ) {
		// parse the query string to find the section to ask for
		// and redirect to an SEO-friendly URL with the keywords
		if (isset($_GET['section'])) {
			header('Location: /nyt/'.urlencode($_GET['section']));
			exit;
		}
		parent::__construct($parameters);
	}

	public function run( $results = '' ) {
		if (!empty($this->parameters)) {
			$results = $this->doSearch();
		}

		$v = new NytView();
		$v->setMessages( $this->messages );
		$v->setSection( $this->parameters );
		$v->run( $results );
	}

	private function doSearch() {
		$m = new NytModel();
		return $m->run($this->parameters);
	}
}
