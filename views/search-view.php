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
	 * Generate the main section of the page.
	 */
	public function getMainSection( $results = '' ) {

		return <<<HTML
{$this->messages}
<div class="row">
	<div class="col-md-12">
		<form method="GET" class="form-horizontal" role="form" action="/search">
		<input type="hidden" name="cmd" value="search"/>
		<div class="form-group">
			<label for="keywords" class="col-md-2 control-label">Search for: </label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="keywords" placehoder="Type search terms here" value=""/>
			</div>
		</div>
		<div class="form-group">
			<label for="engines" class="col-md-2 control-label">Source: </label>
			<div class="checkbox inline">
			<div class="col-md-1">
			<label>
				<input style="margin-top: -6px" type="checkbox" class="form-control" name="engines" value="g"/>
				Google
			</label>
			</div>
			<div class="col-md-1">
			<label>
				<input style="margin-top: -6px" type="checkbox" class="form-control" name="engines" value="b"/>
				Bing
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
	<div class="col-md-offset-1 col-md-11">
$results
	</div>
</div>
HTML;
	}
}
