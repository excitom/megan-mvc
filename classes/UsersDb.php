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
		$createdDate = date('Y-m-d H:i:s');
		$sql =<<<SQL
INSERT INTO users
	( nickName, password, email, firstName, lastName, uid, createdDate )
	VALUES( :nickName, :password, :email, :firstName, :lastName, :uid, :createdDate )
SQL;
		$q = $this->dbh->prepare($sql);
		$q->bindValue( ':nickName', $nickName, PDO::PARAM_STR );
		$q->bindValue( ':password', $password, PDO::PARAM_STR );
		$q->bindValue( ':email', $email, PDO::PARAM_STR );
		$q->bindValue( ':firstName', $firstName, PDO::PARAM_STR );
		$q->bindValue( ':lastName', $lastName, PDO::PARAM_STR );
		$q->bindValue( ':uid', $uid, PDO::PARAM_STR );
		$q->bindValue( ':createdDate', $createdDate, PDO::PARAM_STR );

		// Note: Use a transaction to prevent a race condition
		// between inserting a row and asking the DB for the
		// last inserted Id.
		$id = false;
		try {
			$this->dbh->beginTransaction(); 
			$q->execute();
			$id = $this->dbh->lastInsertId();
			$this->dbh->commit();
		} catch(PDOException $e) {
			$this->dbh->rollback();
			error_log("UserDb error: ".$e->getMessage());
		}
				
		return $id;
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
			if (count($rows)) {
				return $rows;
			} else {
				return false;
			}
		} else {
			return $res;
		}
	}

	public function updateUserByName( $nickName, $password, $email, $firstName, $lastName, $uid ) {
		$sql =<<<SQL
UPDATE users SET email = :email, firstName = :firstName, lastName = :lastName
	WHERE nickName = :nickName
SQL;
		$q = $this->dbh->prepare($sql);
		$q->bindValue( ':nickName', $nickName, PDO::PARAM_STR );
		$q->bindValue( ':email', $email, PDO::PARAM_STR );
		$q->bindValue( ':firstName', $firstName, PDO::PARAM_STR );
		$q->bindValue( ':lastName', $lastName, PDO::PARAM_STR );
		$q->execute();
	}
}
