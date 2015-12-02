<?php
class SearchModel extends Model {
	private $google = false;
	private $bing = false;

	public function __construct( $engines ) {
		if ($engines['g']) {
			$this->google = true;
		}
		if ($engines['b']) {
			$this->bing = true;
		}
	}

	public function run() {
	}
}
