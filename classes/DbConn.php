<?php

/**
 * Implement a singleton class so that a database connection
 * is shared among all code that uses it in a single web page.
 *
 * The class assumes that server connection parameters are stored
 * in the SERVER environment variable.
 */
class DbConn {
	private static $dbh = null;

	private function __construct() {
		// prevent the class from being instantiated
	}

	public static function getConnection() {
		if (self::$dbh === null) {
			self::$dbh = new \PDO( 'mysql:host='.$_SERVER['DB_HOST'].';dbname='.$_SERVER['DB_DEFAULT'].';charset=utf8', $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD']);
		}
		return self::$dbh;
	}
}
