<?php
/*
 * This is the file for launching the framework
 */
date_default_timezone_set('America/Los_Angeles');

/*
 * Autoload function
 */
spl_autoload_register(function ($class) {
	switch($class) {
		case 'Controller':
			require_once $_SERVER['FW_ROOT'] . '/controllers/controller.php';
			break;
		case 'LoggedInController':
			require_once $_SERVER['FW_ROOT'] . '/controllers/loggedin-controller.php';
			break;
		case 'Model':
			require_once $_SERVER['FW_ROOT'] . '/models/model.php';
			break;
		case 'View':
			require_once $_SERVER['FW_ROOT'] . '/views/view.php';
			break;
		default:
			$classPath =  $_SERVER['FW_ROOT'] . '/classes/'.$class.'.php';
			if (file_exists($classPath)) {
				require_once $_SERVER['FW_ROOT'] . '/classes/'.$class.'.php';
			}
	}
});

/*
 * Find the page's class name and invoke the controller.
 *   Example: If the URI is /xxx/yyy/zzz then the
 *   path to the class file is $FW_ROOT/controllers/xxx/yyy/zzz-controller.php
 *   and the class name is XxxYyyZzzController
 */
$class = strtolower($_SERVER['REQUEST_URI']);	// upper/lowercase doesn't matter
$class = preg_replace('/\?.*$/', '', $class);	// remove query string, if any
$class = str_replace('php', '', $class);	// remove the .php, if any
$class = preg_replace('/[^\da-z\/_-]/i', '', $class);	// remove extraneous characters
if ($class == '/') {
	$class = '/index';	// special case
}

/*
 * Trailing parts of the path name my be page parameters. For example:
 * /search/some-keywords  => The page is search-controller.php and 
 *							some-keywords are passed as a parameter.
 * /product/book/isbn  => The page is product-book-controller.php and 
 *							isbn is passed as a parameter.
 */

$found = false;
$parameter = '';
$parts = explode('/', $class);
while (!empty($parts) && !$found) {
	$className = ucwords(str_replace('/', ' ', $class));
	$className = str_replace(' ', '', $className).'Controller';
	$classPath = $_SERVER['FW_ROOT'] . '/controllers' . $class . '-controller.php';

	/*
	 * If the controller file exists, load it and instantiate the class and
	 * call the run() method.
	 * Also, optionally load the model and view classes.
	 */
	if (is_file($classPath)) {
		$found = true;
		require_once $classPath;

		// If there is a corresponding model, preload the class file
		$model = $_SERVER['FW_ROOT'] . '/models' . $class . '-model.php';
		@include_once $model;

		// If there is a corresponding view, preload the class file
		$view = $_SERVER['FW_ROOT'] . '/views' . $class . '-view.php';
		@include_once $view;
		
		$c = new $className( $parameter );
		$c->run();
	} else {
		$parameter = array_pop($parts);
		$class = join('/', $parts);
	}
}

// If all else fails
if (!$found) {
	require_once '404.php';
}
