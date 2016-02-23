<?php
class AjaxScrollController extends Controller {

	private $size = 'medium';
	private $amount = '5';
	private $url = 'http://loripsum.net/api';

	/**
	 * Process an AJAX call for more random text.
	 */
	public function run() {
		$url = $this->url . '/' . $this->amount . '/' . $this->size;
		echo file_get_contents( $url );
    	echo '<a href="/AjaxScroll">more...</a>';
	}
}
