<?php

class NytView extends View {
	private $messages = '';
	private $sections = [
		'home' => 'Front Page',
		'arts' => 'Arts',
		'science' => 'Science',
		'sports' => 'Sports',
		'us' => 'U.S. News',
		'world' => 'World News'
	];
	private $section = 'home';

	public function __construct( $title = 'This page gets current articles from the New York Times' ) {
		parent::__construct( $title );
	}

	public function setSection( $s ) {
		$this->section = $s;
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
		$results = json_decode($results);
		$rows = $this->formatResults( $results );
		$select = [];
		foreach ($this->sections as $s => $name ) {
			$selected = ($s == $this->section) ? ' selected="selected"' : '';
			$select[] =<<<HTML
  <option value="$s"$selected>$name</option>
HTML;
		}
		$select = join("\n", $select); 
		return <<<HTML
<div class="container theme-showcase" role="main">
{$this->messages}
  <div class="jumbotron">
	<h3>Show Current Articles from the New York Times</h3>
	<p>
	This search query will retrieve the current list of top articles
	from a section of the news site. 
	</p>
	<form method="GET" class="form-horizontal" role="form" action="/nyt">
	<input type="hidden" name="cmd" value="nyt"/>
	<div class="form-group">
		<label for="keywords" class="col-md-2 control-label">Section: </label>
		<div class="col-md-8" style="margin-top: 8px">
<select name="section" class="form-select" aria-label="Section selection">
	$select
</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-offset-2 col-md-2">
			<button type="submit" class="btn btn-primary">Search</button>
		</div>
	</div>
  	</form>
  </div>
$rows
HTML;
	}

	/**
	 * Put the search results into HTML format. The results may contain
	 * either or both Google and Bing results.
	 */
	private function formatResults( $results ) {
		if (empty($results)) {
			return '';
		}
		$section = $results->section;
		if ($section == 'home') {
			$section = 'Front Page';
		}
		$rows = [];
		foreach ($results->results as $article) {
			$pd = new DateTimeImmutable($article->published_date);
			$date = $pd->format('Y-m-d H:i:s');
			$mm = $article->multimedia[2];
			if ($article->url) {
				$url = "<a href='{$article->url}'>Read it online</a>";
			} else {
				$url = '';
			}
			$rows[] =<<<HTML
<h4>{$article->title}</h4>
<div class="row">
	<div class="col-sm-2">
		<img width="{$mm->width}" height="{$mm->height}" src="{$mm->url}">
	</div>
	<div class="col-sm-10">
		<p>
		{$article->abstract}
		</p>
		<p>
		{$article->byline}
		<br/>
		Published: $date
		<br/>
		$url
		</p>
	</div>
</div>
HTML;
		}
		$rows = join("\n", $rows);
		return <<<HTML
<div class="row">
	<div class="col-md-12">
		<h2>$section</h2>
	</div>
</div>
$rows
HTML;
	}
}
