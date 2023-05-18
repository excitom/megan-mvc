<?php

class SearchView extends View {
	private $messages = '';

	public function __construct( $title = 'This is a demonstration search page' ) {
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
		$results = $this->formatResults( $results );

		// determine if search query field should be pre-filled
		if (isset($_REQUEST['keywords'])) {
			$keywords = htmlspecialchars($_REQUEST['keywords'],ENT_QUOTES | ENT_HTML401, 'UTF-8');
		} else {
			$keywords = '';
		}

		// determine if checkboxes should be checked
		$bingChecked = isset($_REQUEST['useBing']) ? ' checked="checked"' : '';
		$googleChecked = isset($_REQUEST['useGoogle']) ? ' checked="checked"' : '';

		return <<<HTML
<div class="container theme-showcase" role="main">
{$this->messages}
  <div class="row">
	<div class="col-md-12">
		<h3>Using Search Engines in Parallel</h3>
		<div class="alert alert-danger">
			<h4 class="alert-heading">Oh snap!</h4>
			<p>
			Since I first wrote this code a decade ago, 
			the search engines have decided to close off
			access to their APIs, so this code stopped working.
			</p>
			<p>
			It's too bad, since the backend PHP function utilizes 
			the <i>curl-multi</i> feature to make multiple API
			calls in parallel.
			</p>
			<p>
			See the code <a href="https://github.com/excitom/megan-mvc/blob/main/models/search-model.php">here</a>.
			</p>
		</div>
		<p>
		With this form you can submit a search to either Google or Bing
		or both. If you use both, the searches are submitted in parallel
		so that the results come back simultaneously.
		</p>
		<form method="GET" class="form-horizontal" role="form" action="/search">
		<input type="hidden" name="cmd" value="search"/>
		<div class="form-group">
			<label for="keywords" class="col-md-2 control-label">Search for: </label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="keywords" placeholder="Type search terms here" value="$keywords"/>
			</div>
		</div>
		<div class="form-group">
	      <label class="col-md-2 control-label">Source: </label>
		  <div class="col-md-2">
		    <div class="checkbox">
			  <label for="useGoogle">
			    <input type="checkbox" name="useGoogle" value="1"{$googleChecked}/> Google
		      </label>
			</div>
		  </div>
		  <div class="col-md-2">
		    <div class="checkbox">
			  <label for="useBing">
			    <input type="checkbox" name="useBing" value="1"{$bingChecked}/> Bing
		      </label>
			</div>
		  </div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-2 col-md-2">
				<button type="submit" class="btn btn-primary">Search</button>
			</div>
		</div>
	</div>
  </div>
  </form>
  <div class="row">
$results
  </div>
</div>
HTML;
	}

	/**
	 * Put the search results into HTML format. The results may contain
	 * either or both Google and Bing results.
	 */
	private function formatResults( $results ) {
		if (empty($results) || !is_array($results)) {
			return '';
		}

		$columns = [];
		foreach ($results as $engine => $result) {
			$r = json_decode($result, true);
			$c = '';
			if ($engine == 'g') {
				$c = $this->formatGoogleResult($r);
			}
			elseif ($engine == 'b') {
				$c = $this->formatBingResult($r);
			}
			$columns[] =<<<HTML
<div class="col-md-5">
$c
</div>
HTML;
		}
		return join("\n", $columns);
	}

	/**
	 * Take an array of results from Google and format them with HTML
	 */
	private function formatGoogleResult($r) {
		if (!isset($r['responseStatus']) || $r['responseStatus'] != 200) {
			return '';
		}
		$results = $r['responseData']['results'];

		$rows = [];
		foreach($results as $row) {
			$rows[] =<<<HTML
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		{$row['title']}
		<br/>
		<a href="{$row['unescapedUrl']}">{$row['url']}</a>
		<br/>
		{$row['content']}
		<hr/>
	</div>
</div>
HTML;
		}

		$rows = join("\n", $rows);
		return <<<HTML
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		<h4>Google Results</h4>
	</div>
</div>
$rows
HTML;
	}

	/**
	 * Take an array of results from Bing and format them with HTML
	 */
	private function formatBingResult($r) {
		if (!isset($r['d']['results'])) {
			return '';
		}

		$results = $r['d']['results'];
		$rows = [];

		// note: google's API is only giving me 8 results, not sure how to
		// bump that up. meanwhile, to keep things balanced, don't show
		// more that 8 bing results.
		$count = 1;

		foreach( $results as $row) {
			$rows[] =<<<HTML
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		{$row['Title']}
		<br/>
		<a href="{$row['Url']}">{$row['DisplayUrl']}</a>
		<br/>
		{$row['Description']}
		<hr/>
	</div>
</div>
HTML;
			$count++;
			if ($count > 8) {
				break;
			}
		}

		$rows = join("\n", $rows);
		return <<<HTML
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		<h4>Bing Results</h4>
	</div>
</div>
$rows
HTML;
	}
}
