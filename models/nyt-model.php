<?php
class NytModel extends Model {
	private $nytUrl = "https://api.nytimes.com/svc/topstories/v2/";
	public function __construct() {
	}
	public function run( $query ) {
		$url = $this->nytUrl . $query . '.json?api-key=' . $_SERVER['NYT_API_KEY'];
		$results = $this->runQuery($url);
		return $results;
	}
	private function runQuery( $url ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);

		$results = curl_exec($ch);
		curl_close($ch);
		return $results;
	}
}
