<?php
class View {
	private $title;
	private $docType;
	private $js = array();
	private $css = array();
	private $scriptLinks = array();
	private $cssLinks = array();
	private $metaTags = array();

	public function __construct( $title = "Megan's Framework", $docType = 'HTML' ) {
		$this->title = $title;
		$this->docType = $docType;

		// global css
		$this->addCssLink('//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css');
		$this->addCssLink('//netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css');

		// global javascript
		$this->addScriptLink("//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js");
		$this->addScriptLink("//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js");
		$this->addScriptLink("//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js");

		$css =<<<CSS
.footertext { text-align: center; }
.footerpad { padding-top: 50px; }
CSS;
		$this->addCss($css);

	}

	/*
	 * This is the main function for rendering a web page.
	 */
	public function run( $mainSection = ''  ) {
		$head = $this->getHeadSection();
		$body = $this->getBodySection( $mainSection );
		$js = $this->getInlineJs();
		print <<<HTML
<!DOCTYPE {$this->docType}>
<html>
$head
$body
$js
</html>
HTML;
	}

	/*
	 * Generate the HEAD section of the page
	 */
	protected function getHeadSection() {
		$metaTags = $this->getMetaTags();
		$scriptLinks = $this->getScriptLinks();
		$cssLinks = $this->getCssLinks();
		$css = $this->getInlineCss();
		return <<<HTML
<head>
<title>{$this->title}</title>
$metaTags
$scriptLinks
$cssLinks
$css
</head>
HTML;
	}

	/*
	 * Generate META tags for the page
	 */
	protected function getMetaTags() {
		$tags = '';
		if (!empty($this->metaTags)) {
			$tags = array();
			foreach ($this->metaTags as $tag) {
				$key = $tag[0];
				$value = $tag[1];
				$content = $tag[2];
				$tags[] =<<<HTML
<meta $key="$value" content="$content" />
HTML;
			}
			$tags = join("\n", $tags);
		}
		return $tags;
	}

	public function setMetaTag( $key, $value, $content ) {
		$this->metaTags[] = array( $key, $value, $content );
	}

	/**
	 * Generate a list of javascript files to include with SCRIPT tags
	 */
	protected function getScriptLinks() {
		$scripts = '';
		if (!empty($this->scriptLinks)) {
			$scripts = join("\n", $this->scriptLinks);
		}
		return $scripts;
	}

	/**
	 * Generate an inline Javascript block
	 */
	protected function getInlineJs() {
		return join("\n", $this->js);
	}

    /**
     * Add a link to a SCRIPT URL to the header of the page
     */
	public function addScriptLink( $link ) {
		if (strpos($link, '<script') === false) {
			$link =<<<JS
<script src="$link"></script>
JS;
		}
		$this->scriptLinks[] = $link;
	}

    /**
	 * Add some inline Javascript to the bottom of the page
	 * (should NOT include <script> tags)
	 */
	public function addInlineJs( $js ) {
		$this->js[] = $js;
	}

	/**
	 * Generate a list of CSS files to include
	 */
	protected function getCssLinks() {
		$css = '';
		if (!empty($this->cssLinks)) {
			$css = join("\n", $this->cssLinks);
		}
		return $css;
	}

    /**
     * Add a link to a CSS URL to the header of the page
     */
	public function addCssLink( $link ) {
		if (strpos($link, '<link') === false) {
			$link =<<<CSS
<link rel="stylesheet" href="$link">
CSS;
		}
		$this->cssLinks[] = $link;
	}

	/**
	 * Generate an inline CSS block
	 */
	protected function getInlineCss() {
		$css = '';
		if (!empty($this->css)) {
			$css = join("\n", $this->css);
			$css =<<<HTML
<style type="text/css">
$css
</style>
HTML;
		}
		return $css;
	}

    /**
	 * Add some inline CSS to the header of the page
	 * (should NOT include <style> tags)
	 */
	public function addCss( $css ) {
		$this->css[] = $css;
	}

	/**
	 * Generate the BODY section of the page
	 */
	protected function getBodySection( $mainSection = '' ) {
		$topMargin = $this->getTopMargin();
		$mainSection = $this->getMainSection( $mainSection );
		$footer = $this->getFooter();

		return <<<HTML
<body>
$topMargin
$mainSection
$footer
</body>
HTML;
	}

	/**
	 * Generate the top margin of a web page
	 */
	protected function getTopMargin() {
		$logo = '<img src="/megan-logo.png" alt="Megan Logo" width="121"/>';
		return <<<HTML
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
  <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" style="padding: 5px;" href="/">$logo</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle active" data-toggle="dropdown">Demonstration Functions <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="/search">Search using Google and Bing</a></li>
            <li><a href="/products">Search using Amazon</a></li>
          </ul>
        </li>
      </ul>
	</div>
  </div>
</nav>
HTML;
	}

	/**
	 * Generate the middle section of a web page
	 */
	protected function getMainSection() {
		return <<<HTML
<div class="row">
	<h3 class="col-md-offset-1 col-md-10">PHP Framework</h3>
</div>
HTML;
	}

	/**
	 * Generate the footer of a web page
	 */
	protected function getFooter() {
		$year = date('Y');

		return <<<HTML
<footer class="clearfix">
    <div class="footertext footerpad">
        Copyright &copy; $year Halsoft.com, Inc.
    </div>
</footer>
HTML;
	}
}
