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
		return $this->getRow( 'id', $id );
	}

	public function getUserByName( $nickName ) {
		return $this->getRow( 'nickName', $nickName );
	}

	public function getUserByEmail( $email ) {
		return $this->getRow( 'email', $email );
	}

	private function getRow( $col, $key ) {
		$sql = "SELECT * FROM users WHERE $col = :$col";
		$q = $this->dbh->prepare($sql);
		$q->bindValue( ":$col", $key );
		$res = $q->execute();
		if ($res) {
			$rows = $q->fetchAll(PDO::FETCH_ASSOC);
			return $rows;
		} else {
			return $res;
		}
	}

}
