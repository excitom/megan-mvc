<?php
class IndexController extends Controller {

	public function run() {
		$v = new IndexView();
		$this->setMetaTags( $v );
		$v->run();
	}

	protected function setMetaTags( $v ) {
		// Site description and keywords
		$v->setMetaTag('content', 'description', 'A demonstration website which shows how to build an MVC framework using the PHP language');
		$v->setMetaTag('content', 'keywords', 'PHP, MVC, framework');

		// Set up some meta data for Facebook
		$v->setMetaTag('property', 'og:title', 'halsoft.com');
		$v->setMetaTag('property', 'og:url', 'http://megan.halsoft.com');
		$v->setMetaTag('property', 'og:type', 'website');
	}
}
