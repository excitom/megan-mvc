<?php
/**
 * Make a request to the Google geocode API to get the latitude/longitude 
 * of an input street address.
 */
class MapModel extends Model {
    private $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=';

	public function run( $address ) {
    	// get latitude, longitude and formatted address
    	return $this->geocode($address);
	}

	private function geocode( $address ) {
    	// google map geocode api url
    	$url = $this->url . urlencode($address);
		$url .= '&key=';
		$url .= $_SERVER['GOOGLE_API_KEY'];

    	// get the json response
		$resp_json = file_get_contents($url);
     
    	// decode the json
    	$resp = json_decode($resp_json, true);
 
    	// response status will be 'OK', if able to geocode given address 
    	if ($resp['status']=='OK') {
 
        	// get the important data
        	$latitude = $resp['results'][0]['geometry']['location']['lat'];
        	$longitude = $resp['results'][0]['geometry']['location']['lng'];
        	$formatted_address = $resp['results'][0]['formatted_address'];
         
        	// verify if data is complete
        	if ($latitude && $longitude && $formatted_address) {
         
            	// put the data in the array
            	$data = [];            
             
            	array_push(
                	$data, 
                    	$latitude, 
                    	$longitude, 
                    	$formatted_address
                	);
             
            	return $data;
             
        	} else {
            	return false;
        	}
        	 
    	} else {
        	return false;
    	}
	}
}
