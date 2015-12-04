<?php
class SearchModel extends Model {
	private $ip = '172.30.0.52';
	private $referrer = 'http://megan.halsoft.com/search/';

	private $google = false;
	private $bing = false;

	private $bingKey = "+/rYkHoUU0dxudX1aIcamWjNXjc7gTjaztAL5GB0xQc";
	private $bingUrl = 'https://api.datamarket.azure.com/Bing/Search/';

	private $googleUrl = 'https://ajax.googleapis.com/ajax/services/search/web?v=1.0&q={Query}&userip={IP}&rsz=large';

	// curl handles
	private $gch = null;
	private $bch = null;

	public function __construct( $engines ) {
		if (isset($engines['g'])) {
			$this->google = true;
		}
		if (isset($engines['b'])) {
			$this->bing = true;
		}
	}

	public function setIp( $ip ) {
		$this->ip = $ip;
	}

	public function setReferrer( $referrer ) {
		$this->referrer = $referrer;
	}

	public function run( $query ) {
		$response = 'Failed to set up a search engine';
		if ($this->google) {
			$this->setupGoogle( $query );
		}
		if ($this->bing) {
			$this->setupBing( $query );
		}
		if ($this->gch || $this->bch) {
			$response = $this->runQuery();
		}
		return $response;
	}

	private function setupGoogle( $query ) {
		$this->gch = curl_init();
		curl_setopt($this->gch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->gch, CURLOPT_REFERER, $this->referrer);
		$url = $this->googleUrl;
		$url = str_replace('{Query}', urlencode($query), $url);
		$url = str_replace('{IP}', $this->ip, $url);
		curl_setopt($this->gch, CURLOPT_URL, $url);
	}

	private function setupBing( $query ) {
		$WebSearchURL = $this->bingUrl . 'Web?$format=json&Query=';
		$url = $WebSearchURL . urlencode( '\''.$query.'\'');

		$ch = curl_init();
		$headers = array(
			"Authorization: Basic " . base64_encode($this->bingKey . ":" . $this->bingKey)
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,5);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$this->bch = $ch;
	}

	private function runQuery() {
  
		// build the multi-curl handle, adding both curl handles
		$mh = curl_multi_init();
		if ($this->gch) {
			curl_multi_add_handle($mh, $this->gch);
		}
		if ($this->bch) {
			curl_multi_add_handle($mh, $this->bch);
		}
  
		// execute all queries simultaneously, and continue when all are complete
		$running = null;
		do {
			curl_multi_exec($mh, $running);
		} while ($running);

		// close the handles
		if ($this->gch) {
			curl_multi_remove_handle($mh, $this->gch);
		}
		if ($this->bch) {
			curl_multi_remove_handle($mh, $this->bch);
		}
		curl_multi_close($mh);
  
		// all of our requests are done, we can now access the response
		$response = array();
		if ($this->gch) {
			$response['g'] = curl_multi_getcontent($this->gch);
			curl_close($this->gch);
		}
		if ($this->bch) {
			$response['b'] = curl_multi_getcontent($this->bch);
			curl_close($this->bch);
		}
		return $response;
	}
}
