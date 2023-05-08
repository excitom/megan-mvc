<?php
class View {
	private $title;
	private $docType;
	private $js = array();
	private $css = array();
	private $scriptLinks = array();
	private $cssLinks = array();
	private $metaTags = array();
	private $modalWindows = array();
	private $cdnUrl = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6';

	// keep track of which menu item is active in the nav bar
	private $navActive = array( 'home' => '', 'about' => '', 'contact' => '', 'examples' => '' );

	public function __construct( $title = "The Megan PHP Framework", $docType = 'HTML' ) {
		$this->title = $title;
		$this->docType = $docType;

		// global css
		$this->addCssLink($this->cdnUrl.'/css/bootstrap.min.css');
		$this->addCssLink($this->cdnUrl.'/css/bootstrap-theme.min.css');

		// global javascript
		$this->addScriptLink("//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js");
		$this->addScriptLink("//ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js");
		$this->addScriptLink($this->cdnUrl.'/js/bootstrap.min.js');

		$css =<<<CSS
.footertext { text-align: center; }
.footerpad { padding-top: 50px; }
.navbar-brand {margin-top: -8px; }
CSS;
		$this->addCss($css);

		$this->setTrackingCookie();

	}

	/**
	 * We track visitors to the website by setting a unique ID string into
	 * a cookie.
	 */
	private function setTrackingCookie() {
		if (!isset($_COOKIE['u'])) {
			// create a unique string
			$uid = md5($_SERVER['REMOTE_ADDR'].$_SERVER['REQUEST_TIME']);
			// expire 6 months in the future
			$expires = time()+60*60*24*30*6;
			$domain = Cookies::getThisDomainName();
			setcookie( 'u', $uid, $expires, '/', $domain);
		}
	}

	/*
	 * This is the main function for rendering a web page.
	 */
	public function run( $mainSection = ''  ) {
		$head = $this->getHeadSection();
		$body = $this->getBodySection( $mainSection );
		print <<<HTML
<!DOCTYPE {$this->docType}>
<html>
$head
$body
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
		$tags = array();

		// start with required tags for responsiveness
		$tags[] = '<meta charset="utf-8">';
		$tags[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
		$tags[] = '<meta name="viewport" content="width=device-width, initial-scale=1">';

		// add optional tags per page
		if (!empty($this->metaTags)) {
			foreach ($this->metaTags as $tag) {
				$key = $tag[0];
				$value = $tag[1];
				$content = $tag[2];
				$tags[] =<<<HTML
<meta $key="$value" content="$content" />
HTML;
			}
		}
		$tags = join("\n", $tags);
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
		$js = join("\n", $this->js);
		return <<<HTML
<script type="text/javascript">
$js
</script>
HTML;
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
		$modalWindows = $this->getModalWindows();
		$js = $this->getInlineJs();

		return <<<HTML
<body role="document">
$topMargin
$mainSection
$footer
$modalWindows
$js
</body>
HTML;
	}

    /**
	 * Add a modal window to the page
	 */
	public function addModalWindow( $html ) {
		$this->modalWindows[] = $html;
	}

	/**
	 * Generate HTML for modal windows, if any
	 */
	protected function getModalWindows() {
		$html = '';
		if (!empty($this->modalWindows)) {
			$html = join("\n", $this->modalWindows);
		}
		return $html;
	}

	/**
	 * Generate the top margin of a web page
	 */
	protected function getTopMargin() {
		$logo = $this->getLogo();
		$menu = $this->getMenu();
		$userArea = $this->getUserArea();

		return <<<HTML
    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
		  $logo
        </div>
        <div id="navbar" class="navbar-collapse collapse">
$menu
$userArea
        </div><!--/.nav-collapse -->
      </div><!--/.container -->
    </nav>
    <div class="page-header">
    </div>
HTML;
	}

	private function getLogo() {
		$logo = '<img src="/megan-logo.png" alt="Megan Logo"/>';
        return <<<HTML
<a class="navbar-brand" href="/">$logo</a>
HTML;
	}

	public function setNavBarActive( $item ) {
		$this->navActive[$item] = ' class="active"';
	}

	private function getMenu() {
		return <<<HTML
<ul class="nav navbar-nav">
  <li{$this->navActive['home']}><a href="/">Home</a></li>
  <li{$this->navActive['about']}><a href="/about">About</a></li>
  <li{$this->navActive['contact']}><a href="/contact">Contact</a></li>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Examples <span class="caret"></span></a>
    <ul class="dropdown-menu">
      <li><a href="/search">Search using Google and Bing</a></li>
      <li><a href="/products">Search using Amazon</a></li>
      <li><a href="/sms">Send an SMS message using Twilio</a></li>
      <li><a href="/map">Display a street address on a Googe Map</a></li>
      <li><a href="/scroll">Demonstrate Infinite Scrolling</a></li>
    </ul>
  </li>
</ul> <!-- navbar-nav -->
HTML;
	}

	/**
	 * Get the user area for the right side of the nav bar.
	 * This includes:
	 * - if anonymous user, a "register now" link
	 * - if recognized user but not logged in, a login link
	 * - if logged in, 
	 *   -- cart contents (if any)
	 *   -- "my account" link
	 *   -- name
	 *   -- logout link
	 */
	private function getUserArea() {
		if (!isset($_COOKIE['n'])) {
			return $this->getAnonymousUser();
		}
		elseif (!isset($_COOKIE['s'])) {
			return $this->getLoggedOutUser();
		}
		else {
			return $this->getLoggedInUser();
		}
	}

	/**
	 * If we don't recognize the web page visitor (since no cookies are set)
	 * show a button to encourage registering for the site. Clicking the button
	 * opens a modal window containing a registration form.
	 */
	private function getAnonymousUser() {
		// add the javascript for manioulating the window
		$this->getRegisterJs();

		// add the HTML for the modal window itself
		$this->getRegisterModal();

		// return the button for invoking the modal window
		return <<<HTML
<ul class="nav navbar-nav navbar-right">
  <li>
    <button style="margin-top: 10px;" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registerModal">Register Now!</button>
  </li>
</ul>
HTML;
	}

	/**
	 * Generate the HTML for the registration modal window
	 */
	private function getRegisterModal() {
		$form = Forms::getRegisterForm();

		$html =<<<HTML
<div id="registerModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Register for this Test Community</h4>
      </div>
 $form
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
HTML;
		$this->addModalWindow( $html );
	}

	/**
	 * Generate the javascript for the registration modal window
	 */
	private function getRegisterJs() {
		$js = Forms::getRegisterJs();
		$this->addInlineJs( $js );
	}

	private function getLoggedOutUser() {
		// add the javascript for manioulating the window
		$this->getLoginJs();

		// add the HTML for the modal window itself
		$this->getLoginModal();
		return <<<HTML
<ul class="nav navbar-nav navbar-right">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome back {$_COOKIE['n']} <span class="caret"></span></a>
    <ul class="dropdown-menu">
      <li>
	  	<a href="#" role="button" data-toggle="modal" data-target="#loginModal">Sign In</a>
   	  </li>
    </ul>
  </li>
</ul>
HTML;
	}

	/**
	 * Generate the javascript for the login modal window
	 */
	private function getLoginJs() {
		$this->addInlineJs(Forms::getLoginFormJs());
	}

	/**
	 * Generate the HTML for the login modal window
	 */
	private function getLoginModal() {

		$nickName = htmlentities($_COOKIE['n']);
		$loginForm = Forms::getLoginForm( $nickName );

		$html =<<<HTML
<div id="loginModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Sign In to this Test Community</h4>
		<h4 id="modalMsg"></h4>
      </div>
      <div class="modal-body">
$loginForm
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
HTML;
		$this->addModalWindow( $html );
	}

	/**
	 * There is a session cookie so a user is logged in
	 */
	private function getLoggedInUser() {
		return <<<HTML
<ul class="nav navbar-nav navbar-right">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome {$_COOKIE['n']} <span class="caret"></span></a>
    <ul class="dropdown-menu">
      <li><a href="/myaccount">My Account</a></li>
      <li><a href="/logout">Sign Out</a></li>
    </ul>
  </li>
</ul>
HTML;
	}

	/**
	 * Generate the middle section of a web page
	 */
	protected function getMainSection() {
		return <<<HTML
<div class="container theme-showcase" role="main">
  <div class="jumbotron">
	<h2>PHP Framework</h2>
	<p>
	If you are seeing this, the page containing this class did not
	build its own main section.
	</p>
  </div>
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
