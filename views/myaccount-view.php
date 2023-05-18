<?php

class MyaccountView extends View {
	private $messages = '';

	public function __construct( $title = 'My Account' ) {
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
	public function getMainSection( $userInfo = [] ) {

		return <<<HTML
<div class="container theme-showcase" role="main">
{$this->messages}
	<h3>My Account</h3>
	<form method="POST" class="form-horizontal" role="form" action="/myaccount">
	<input type="hidden" name="cmd" value="myaccount"/>
	<div class="mb-3">
		<label class="form-label">User Name:</label>
		<span class="help-inline">
			{$userInfo['nickName']}
		</span>
	</div>
	<div class="form-row mb-3">
		<label for="email" class="form-label">Email:</label>
		<input type="email" class="form-control" name="email" value="{$userInfo['email']}">
	</div>
	<div class="form-row mb-3">
		<label for="firstName" class="form-label">First Name:</label>
		<input type="text" class="form-control" name="firstName" value="{$userInfo['firstName']}">
	</div>
	<div class="form-row mb-3">
		<label for="lastName" class="form-label">Last Name:</label>
		<input type="text" class="form-control" name="lastName" value="{$userInfo['lastName']}">
	</div>
	<div class="form-row mb-3">
		<button type="submit" class="btn btn-primary">Update</button>
	</div>
	</form>
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
