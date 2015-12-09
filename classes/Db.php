<?php
/**
 * Base class for DB objects.
 */
class Db {
	protected $dbh = null;

	public function __construct() {
		$this->dbh = DbConn::getConnection();
	}
}
