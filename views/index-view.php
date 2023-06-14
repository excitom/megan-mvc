<?php

class IndexView extends View {
	public function __construct( $title = 'Megan PHP Framework - This is the home page' ) {
		$this->setNavBarActive('home');
		parent::__construct( $title );
		$this->addScriptLink('/parallax.js-1.5.0/parallax.min.js');
		$css =<<<CSS
.parallax-window {
    min-height: 400px;
    background: transparent;
}
.jumbotron p {
    font-size: 14px;
}
li {
    font-weight: 200;
}
CSS;
		$this->addCss($css);
	}

	/**
	 * Generate the middle section of the home page
	 */
	protected function getMainSection() {
		return <<<HTML
<dic class="parallax-window" data-parallax="scroll" data-image-src="/IMG_1398.jpeg"></div>
<div class="container theme-showcase" role="main">
  <div class="jumbotron">
	<h2>Megan PHP Framework</h2>
    <h3>Overview</h3>
	<p>
	This is a demonstration framework that I created for building web pages,
	implemented in PHP.
    I started the implementation in 2015 and I update it periodically.
    More documentation and the entire source code may be found on
	<a href="https://github.com/excitom/megan-mvc">github</a>.
	Check the Examples menu above for various demonstrations.
	</p>
	<p>
	Megan is the name of my youngest daughter.
	</p>
  </div>
  <div class="jumbotron">
    <h3>Framework</h3>
    <ul>
    <li>The project is completely self-contained and does not rely on
any framework (Laravel, Zend, Symfony, Cake, etc.). Why write it all
from scratch? It's more fun and you learn more that way!</li>
    <li>There is only a single <code>php</code> file in the <code>doc root</code>, it is <code>index.php</code>,
and this file only contains a few lines that invoke the framework (<code>fw.php</code>) which is located outside the document root for better isolation.</li>
    <li>The framework relies on the NGINX <code>try_files</code> directive to send all URIs
which don't correspond to an existing file to the <code>index.php</code>.</li>
    <li>The framework supports a <code>model-view-controller (MVC)</code> design pattern.
The control logic is in the <code>controller</code>, the data comes from the <code>model</code>, and
the presentation of the web page is handled in the <code>view</code>.</li>
    <li>The framework parses the URI into one or more directory names and the
file name, which corresponds to a PHP class name.
For example: <code>FW_ROOT</code> is the directory containing the framework.
If the URI is <code>xxx/yyy/zzz</code> then the
path to the class file is <code>FW_ROOT/controllers/xxx/yyy/zzz-controller.php</code>
and the class name is <code>XxxYyyZzzController</code>.
Depending on the controller, it may invoke a model. The
path to the class file is <code>FW_ROOT/models/xxx/yyy/zzz-model.php</code>
and the class name is <code>XxxYyyZzzModel</code>.
Similarly the controller may invoke a view. The
path to the class file is <code>FW_ROOT/views/xxx/yyy/zzz-views.php</code>
and the class name is <code>XxxYyyZzzView</code>.</li>
    <li>upporting classes are found similarly. For example, the <code>Cookies</code> class
is in <code>FW_ROOT/classes/Cookies.php</code>.</li>
    <li>The PHP <code>autoloader</code> feature is used to find the class files, removing
the need for explicit <code>require</code> or <code>include</code> statements.</li>
    <li>The MVC classes rely heavily on object-oreiented class inheritance.
For example the <code>View</code> class does most of the work for generating a web
page. Pages like the <code>login</code> page are implemented by the <code>LoginView</code> class
which extends <code>View</code>.</li>
    </ul>
  </div>
  <div class="jumbotron">
<h3>Database</h3>
<ul>
<li>The framework supports CRUD database operations. Originally it
used <code>mysql</code> but I recently switched to <code>mariadb</code> with few changes required.</li>
<li>There is a singleton <code>DbConn</code> class that allows multiple classes
within a page to share a common DB connection.</li>
</ul>
  </div>
  <div class="jumbotron">
<h3>Presentation</h3>
<ul>
<li>The front end code is built upon <a href="https://getbootstrap.com/docs/3.4/css/">Bootstrap</a> for CSS/presentation and <a href="https://jquery.com/">jQuery</a> for javascript/interactivity. In this day and age modern front end projects tend to be built on a javascript framework such as <code>Angular</code> or <code>React</code> but I find these are overkill for a relatively simple set of web pages such as this site.</li>
</ul>
  </div>
</div>
HTML;
	}
}
