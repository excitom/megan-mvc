<?php
/**
 * Manage the "log" database table.
 *
 * This is a general purpose log file, searchable on date range or the
 * keyword column or the userid.
 *
 */
class LogDb extends Db {

	public function add( $keyword, $message, $userid = null ) {
		$sql =<<<SQL
INSERT INTO log
	( keyword, message, userid )
	VALUES( :keyword, :message, :userid )
SQL;
		$q = $this->dbh->prepare($sql);
		$q->bindValue( ':keyword', $keyword, PDO::PARAM_STR );
		$q->bindValue( ':message', $message, PDO::PARAM_STR );
		$q->bindValue( ':userid', $userid, PDO::PARAM_INT );

		return $q->execute();
	}
}
