<?php

class ProductsView extends View {
	private $messages = '';

	public function __construct( $title = 'This page uses Amazon search' ) {
		parent::__construct( $title );
	}

	/**
	 * If error messages are passed in, build an block to
	 * put at the top of the main section.
	 */
	public function setMessages( $messages ) {
		if (!empty($messages)) {
			$m = array();
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
		if (empty($results)) {
			return $this->showSearchBox();
		} else {
			return $this->showSearchResults( $results );
		}
	}

	private function showSearchBox() {

		// determine if search query field should be pre-filled
		if (isset($_REQUEST['keywords'])) {
			$keywords = htmlspecialchars($_REQUEST['keywords'],ENT_QUOTES | ENT_HTML401, 'UTF-8');
		} else {
			$keywords = '';
		}

		return <<<HTML
<div class="container theme-showcase" role="main">
{$this->messages}
  <div class="row">
	<div class="col-md-12">
      <h3>Using Amazon Product Search</h3>
      <p>Type keywords to search in Amazon's index of books.</p>
    </div>
  </div>
  <div class="row">
	<div class="col-md-12">
		<form method="GET" class="form-horizontal" role="form" action="/products">
		<input type="hidden" name="cmd" value="search"/>
		<div class="form-group">
			<label for="keywords" class="col-md-2 control-label">Search for: </label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="keywords" placeholder="Type search terms here" value="$keywords"/>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-2">
				<button type="submit" class="btn btn-primary">Search</button>
			</div>
		</div>
	</div>
  </div>
</div>
</form>
HTML;
	}

	/**
	 * Put the search results into HTML format. 
	 */
	private function showSearchResults( $results ) {
		$results = $this->decodeResults( $results );
		if (!isset($results['Items']['Item']) || empty($results['Items']['Item'])) {
			return <<<HTML
<div class="row">
  <div class="col-md-offset-1 col-md-10">
    <h3 class="text-danger">Sorry, nothing found</h3>
  </div>
</div>
HTML;
		} else {
			$rows = array();
			foreach( $results['Items']['Item'] as $item ) {
				$author = $this->getAuthor( $item['ItemAttributes']['Author'] );
				$price = $this->getPrice( $item['OfferSummary'] );
				$rows[] =<<<HTML
<div class="row">
  <div class="col-md-offset-1 col-md-1">
  	<img src="{$item['SmallImage']['URL']}" width={$item['SmallImage']['URL']} height={$item['SmallImage']['Height']} width="{$item['SmallImage']['Width']}" alt="{$item['ItemAttributes']['ISBN']}" />
  </div>
  <div class="col-md-10">
    <h3><a href="/isbn/{$item['ItemAttributes']['ISBN']}">{$item['ItemAttributes']['Title']}</a></h3>
	<p>Author: <strong>$author</strong><p>
	<p>List Price: <strong>{$item['ItemAttributes']['ListPrice']['FormattedPrice']}</strong><p>
	<p>Buy as low as: <strong>$price</strong></p>
  </div>
 </div>
 <hr/>
HTML;
			}
			return join("\n", $rows);
		}
	}

	private function getAuthor( $author ) {
		if (is_array($author)) {
			return join(', ', $author);
		} else {
			return $author;
		}
	}

	private function getPrice( $price ) {
		return $price['LowestNewPrice']['FormattedPrice'];
	}

	/**
	 * Decode the XML into PHP hash-array format.
	 */
	private function decodeResults( $results ) {
		if (empty($results)) {
			return '';
		}

		$xml = simplexml_load_string($results, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		$results = json_decode($json,TRUE);
		return $results;
	}
}
