<?php
/**
 * A wrapper for mcrypt cryptography functions.
 *
 * Note: mcrypt has been abandoned and is no longer supported. 
 * The state of the art for encryption has evolved rapidly. 
 */
class Crypto {
	public static function encrypt($data) {
		$firstKey = base64_decode($_SERVER['ENCRYPTION_KEY_1']);
		$secondKey = base64_decode($_SERVER['ENCRYPTION_KEY_2']);
		$method = $_SERVER['CIPHER'];
		$ivLength = openssl_cipher_iv_length($method);
		$iv = openssl_random_pseudo_bytes($ivLength);
		$firstEncrypted = openssl_encrypt($data, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
		$secondEncrypted = hash_hmac('sha3-512', $firstEncrypted, $secondKey, TRUE);
		return base64_encode($iv.$secondEncrypted.$firstEncrypted);
	} 

	public static function decrypt($data) {
		$firstKey = base64_decode($_SERVER['ENCRYPTION_KEY_1']);
		$secondKey = base64_decode($_SERVER['ENCRYPTION_KEY_2']);
		$mix = base64_decode($data);
		$method = $_SERVER['CIPHER'];
		$ivLength = openssl_cipher_iv_length($method);
		$iv = substr($mix, 0, $ivLength);
		$secondEncrypted = substr($mix, $ivLength, 64);
		$firstEncrypted = substr($mix, $ivLength+64);
		$data = openssl_decrypt($firstEncrypted, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
		$secondEncryptedNew = hash_hmac('sha3-512', $firstEncrypted, $secondKey, TRUE);
		if (hash_equals($secondEncrypted, $secondEncryptedNew)) {
			return $data;
		} else {
			return false;
		}
	}
}
