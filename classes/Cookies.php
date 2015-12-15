<?php
class Cookies {
	public static function getThisDomainName() {
		// split the domain name apart
		$d = explode('.', $_SERVER['SERVER_NAME']);
		// get the last piece
		$d2 = array_pop($d);
		// get the second to last piece
		$d1 = array_pop($d);
		// get the base domain name (regardless of how many parts it has)
		return '.'.$d1.'.'.$d2;
	}

	/**
	 * Set a session cookie 's' to indicate a logged in session.
	 * Set a name cookie 'n' containing the user's login name.
	 * Set a first name cookie 'f' containing the user's first name.
	 */
	public static function setLoginCookies( $nickName, $id, $firstName = '', $lastName = '' ) {

		// The session cookie is an encrypted string containing
		// the user name, the user id key (from the DB),
		// and a timestamp
		$str = $nickName .':'. $id .':'. time();
		$str = Crypto::encrypt( $str, $_SERVER['ENCRYPTION_KEY']);

		// split the domain name apart
		$d = explode('.', $_SERVER['SERVER_NAME']);
		// get the last piece
		$d2 = array_pop($d);
		// get the second to last piece
		$d1 = array_pop($d);
		// get the base domain name (regardless of how many parts it has)
		$domain = '.'.$d1.'.'.$d2;

		setcookie( 's', $str, 0, '/', $domain);

		// if the user gave us a first name, use that for the name 
		// cookie. else use the nickname.
		$nm = (empty($firstName)) ? $nickName : $firstName;

		// expire 6 months in the future
		$expires = time()+60*60*24*30*6;
		setcookie( 'f', $nm, $expires, '/', $domain);
		setcookie( 'n', $nickName, $expires, '/', $domain);
	}

	/**
	 * Check if a user is logged in
	 */
	public static function isLoggedIn() {
		if (empty($_COOKIE['s'])) {
			return false;
		} else {
			$str = Crypto::decrypt( $_COOKIE['s'], $_SERVER['ENCRYPTION_KEY']);
			$fields = explode(':', $str);
			return $fields[1];	// return the userid
		}
	}
}
