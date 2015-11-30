<?php

class SearchView extends View {
	public function __construct( $title = 'This is a demonstration search page' ) {
		parent::__construct( $title );
	}

	public function getMainSection( $results ) {
		return <<<HTML
<div class="row">
<form method="GET" class="form-horizontal" role="form" action="/search">
<input type="hidden" name="cmd" value="search"/>
	<div class="col-md-12">
		<div class="form-group">
			<label for="keywords" class="col-md-2 control-label">Search for: </label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="keywords" placehoder="Type search terms here" value=""/>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="form-group">
		<div class="col-md-2">
			<label for="engines" class="col-md-2 control-label">Source: </label>
		</div>
		<div class="col-md-2">
			<div class="checkbox">
				<label>
				<input type="checkbox" class="form-control" name="engines" value="g"/>
				Google
				</label>
			</div>
		</div>
		<div class="col-md-2">
			<div class="checkbox">
				<label>
				<input type="checkbox" class="form-control" name="engines" value="b"/>
				Bing
				</label>
			</div>
		</div>
	</div>
</div>
</form>
HTML;
	}
}
