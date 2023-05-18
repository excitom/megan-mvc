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
<div class="alert alert-danger">
	<h4 class="alert-heading">Oh snap!</h4>
	<p>
	Since I first wrote this code a decade ago 
	Amazon has locked down their free search APIs,
	so this code stopped working until I set up an account as
	a seller.
	</p>
	<p>
	It's too bad, since this was a nice showcase for building SEO-fiendly
	URLs based on the product search. For example if you searched for
	"electronics headphones"  the result URL would be "/products/electronics/headphones".
	</p>
	<p>
	You can see the code. The <a href="https://github.com/excitom/megan-mvc/blob/main/controllers/products-controller.php">controller</a> generates
	the SEO URL and redirects to it, the <a href="https://github.com/excitom/megan-mvc/blob/main/models/products-model.php">model</a>
	does the product search, and the 
	<a href="https://github.com/excitom/megan-mvc/blob/main/views/products-view.php">view</a> decodes and formats the search results.
	</p>
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
		if (!isset($results['Items']['Item']) ||
			 empty($results['Items']['Item']))
		{
			return <<<HTML
<div class="row">
  <div class="col-md-offset-1 col-md-10">
    <h3 class="text-danger">Sorry, nothing found</h3>
  </div>
</div>
HTML;
		} else {
			$rows = [];
			foreach( $results['Items']['Item'] as $item ) {
				if (!empty($item['ItemAttributes']['ISBN'])) {
					$isbn = $item['ItemAttributes']['ISBN'];
					$url = "/isbn/$isbn";
				}
				elseif (!empty($item['ItemAttributes']['EISBN'])) {
					$isbn = $item['ItemAttributes']['EISBN'];
					$url = "/eisbn/$isbn";
				}
				else {
					continue;
				}

				$author = $this->getAuthor( $item['ItemAttributes']['Author'] );
				$price = $this->getPrice( $item );
				$listPrice = $this->getListPrice( $item['ItemAttributes'] );
				$rows[] =<<<HTML
<div class="row">
  <div class="col-md-offset-1 col-md-1">
  	<img src="{$item['SmallImage']['URL']}" width={$item['SmallImage']['URL']} height={$item['SmallImage']['Height']} width="{$item['SmallImage']['Width']}" alt="$isbn" />
  </div>
  <div class="col-md-10">
    <h3><a href="$url">{$item['ItemAttributes']['Title']}</a></h3>
	<p>Author: <strong>$author</strong><p>
	<p>List Price: <strong>$listPrice</strong><p>
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

	private function getListPrice( $price ) {
		if (empty($price['ListPrice']['FormattedPrice'])) {
			return 'not available';
		} else {
			return $price['ListPrice']['FormattedPrice'];
		}
	}

	private function getPrice( $price ) {
		if (empty($price['OfferSummary']['LowestNewPrice']['FormattedPrice'])) {
			return 'not available';
		} else {
			return $price['OfferSummary']['LowestNewPrice']['FormattedPrice'];
		}
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
