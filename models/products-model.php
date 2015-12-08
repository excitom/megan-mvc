<?php
/**
 * Make a request to the Amazon Product Search API
 */
class ProductsModel extends Model {
	private $awsAccessKey = '';
	private $awsSecretKey = '';
	private $awsEndpoint = 'webservices.amazon.com';
	private $associateTag = '';
	private $uri = '/onca/xml';

	public function __construct() {
		$this->awsAccessKey = $_SERVER['AWS_ACCESS_KEY'];
		$this->awsSecretKey = $_SERVER['AWS_SECRET_KEY'];
		$this->associateTag = $_SERVER['AWS_ASSOCIATE_TAG'];
	}

	public function run( $keywords ) {
		$url = $this->setupUrl( $keywords );
		return $this->runQuery( $url );
	}

	private function setupUrl( $keywords ) {
		$keywords = urlencode($keywords);
		$params = array(
    		"Service" => "AWSECommerceService",
    		"Operation" => "ItemSearch",
    		"AWSAccessKeyId" => $this->awsAccessKey,
    		"AssociateTag" => $this->associateTag,
    		"SearchIndex" => "Books",
    		"Keywords" => $keywords,
    		"ResponseGroup" => "Images,ItemAttributes,Offers",
    		"Sort" => "price"
		);

		// Set current timestamp 
		$params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');

		// Sort the parameters by key
		ksort($params);

		$pairs = array();

		foreach ($params as $key => $value) {
			array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
		}

		// Generate the canonical query
		$canonicalQueryString = join("&", $pairs);

		// Generate the string to be signed
		$stringToSign = "GET\n".$this->awsEndpoint."\n".$this->uri."\n".$canonicalQueryString;

		// Generate the signature required by the Product Advertising API
		$signature = base64_encode(hash_hmac("sha256", $stringToSign, $this->awsSecretKey, true));

		// Generate the signed URL
		return 'http://'.$this->awsEndpoint.$this->uri.'?'.$canonicalQueryString.'&Signature='.rawurlencode($signature);
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
