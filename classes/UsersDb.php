<?php
/**
 * Manage the "users" database table.
 *
 * The nickName and email must be unique.
 *
 * The uid is an MD5 hash of a random number that is generated when the 
 * user visits the website. It is used to track anonymous visitors. When
 * a person registers we save the uid to correlate with the previous 
 * activity.
 */
class UsersDb extends Db {

	public function addUser( $nickName, $password, $email, $firstName = null, $lastName = null, $uid = null ) {
		$sql =<<<SQL
INSERT INTO users
	( nickName, password, email, firstName, lastName, uid )
	VALUES( :nickName, :password, :email, :firstName, :lastName, :uid )
SQL;
		$q = $this->dbh->prepare($sql);
		$q->bindValue( ':nickName', $nickName, PDO::PARAM_STR );
		$q->bindValue( ':password', $password, PDO::PARAM_STR );
		$q->bindValue( ':email', $email, PDO::PARAM_STR );
		$q->bindValue( ':firstName', $firstName, PDO::PARAM_STR );
		$q->bindValue( ':lastName', $lastName, PDO::PARAM_STR );
		$q->bindValue( ':uid', $uid, PDO::PARAM_STR );
		$res = $q->execute();
		return $res;
	}

	public function getUserById( $id ) {
		#$dbh
	}


}
