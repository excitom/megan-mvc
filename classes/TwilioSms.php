<?php
/**
 * Send an SMS using Twilio.
 */
require $_SERVER['TW_PATH'].'/Twilio.php';

class TwilioSms {
	private $fromNumber;
	private $twilio;
	private $toNumbers = array();

	public function __construct() {
		$this->fromNumber = $_SERVER['TW_PHONE'];
		$this->twilio = new Services_Twilio($_SERVER['TW_SID'], $_SERVER['TW_AUTH']);
	}

	/**
	 * Add an outgoing phone number
	 */
	public function addNumber( $number ) {
		$number = preg_replace('/[^0-9]/', '', $number);
		$this->toNumbers[] = '+1'.$number;
	}

	/**
	 * Send an SMS message
	 */
	public function send( $message ) {
		$message = htmlentities($message);
		foreach( $this->toNumbers as $number ) {
			$sms = $this->twilio->account->messages->sendMessage(
						$this->fromNumber,
						$number,
						$message
					);
		}
	}
}
