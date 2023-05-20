<?php

class MapView extends View {
	private $messages = '';

	public function __construct( $title = 'This page uses Google Maps' ) {
		parent::__construct( $title );
		$js =<<<JS
  (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
    v: "weekly",
  });
JS;
		$this->addInlineJs($js);
		$map = "<script async src='https://maps.googleapis.com/maps/api/js?key=";
		$map .= $_SERVER['GOOGLE_API_KEY'];
		$map .= "&callback=initMap'></script>";
		$this->addScriptLink($map);
		$css =<<<CSS
#gmap_canvas {
	width: 100%;
	height: 400px;
}
CSS;
		$this->addCss($css);
	}

	/**
	 * If error messages are passed in, build an block to
	 * put at the top of the main section.
	 */
	public function setMessages( $messages ) {
		if (!empty($messages)) {
			$m = [];
			foreach ($messages as $message) {
				$m[] =<<<HTML
<h3 class="text-danger">$message</h3>
HTML;
			}
			$m = join("\n", $m);
			$this->messages =<<<HTML
<div class="row">
	<div class="col-md-offset-1 col-md-11">
$m
	</div>
</div>
HTML;
		}
	}

	/**
	 * Generate the main section of the search results pagepage.
	 */
	public function getMainSection( $results = '' ) {
		$html = $this->showSearchBox();
		if (!empty($results)) {
			$html .= $this->showSearchResults( $results );
		}
		return <<<HTML
<div class="container theme-showcase" role="main">
$html
</div>
HTML;
		return $html;
	}

	private function showSearchBox() {

		// determine if search query field should be pre-filled
		if (isset($_REQUEST['address'])) {
			$address = htmlspecialchars($_REQUEST['address'],ENT_QUOTES, 'UTF-8');
		} else {
			$address = '';
		}

		return <<<HTML
{$this->messages}
  <div class="row">
	<div class="col-md-12">
      <h3>Using Google Maps</h3>
      <p>Type an address you wish to display on a map.</p>
    </div>
  </div>
  <div class="row">
	<div class="col-md-12">
		<form method="GET" class="form-horizontal" role="form" action="/map">
		<input type="hidden" name="cmd" value="search"/>
		<div class="form-group">
			<label for="address" class="col-md-2 control-label">Search for: </label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="address" placeholder="Type street address here" value="$address"/>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-2">
				<button type="submit" class="btn btn-primary">Search</button>
			</div>
		</div>
	    </form>
	</div>
  </div>
HTML;
	}

	/**
	 * Put the search results into HTML format. 
	 */
	private function showSearchResults( $results ) {

    	if (!$results) {
			return <<<HTML
<div class="row">
  <div class="col-md-offset-1 col-md-10">
    <h3 class="text-danger">Sorry, nothing found</h3>
  </div>
</div>
HTML;
		} else {
        	$latitude = $results[0];
        	$longitude = $results[1];
        	$formatted_address = $results[2];

			$js =<<<JAVASCRIPT
let map;
async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");
  map = new Map(document.getElementById("gmap_canvas"), {
    center: { lat: $latitude, lng: $longitude },
    zoom: 14,
	mapTypeId: google.maps.MapTypeId.ROADMAP
  });
	marker = new google.maps.Marker({
			map: map,
			position: new google.maps.LatLng($latitude,$longitude)
		});
	infowindow = new google.maps.InfoWindow({
			content: "$formatted_address"
		});
	google.maps.event.addListener(marker, "click", function () {
		infowindow.open(map, marker);
	});
	infowindow.open(map, marker);
}

initMap();
JAVASCRIPT;
			$this->addInlineJs($js);

			return <<<HTML
<div class="row">
  <div class="col-md-12">
    <!-- google map will be shown here -->
    <div id="gmap_canvas">Loading map...</div>
  </div>
</div>
HTML;
		}
	}
}
