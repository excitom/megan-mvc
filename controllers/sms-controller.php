<?php
class SmsController extends Controller {

	public function run() {
		$v = new SmsView();
		if (!empty($_REQUEST['number']) && !empty($_REQUEST['message'])) {
			$tw = new TwilioSms();
			$number =preg_replace('/[^0-9]/', '', $_REQUEST['number']);
			$tw->addNumber($number);
			$tw->send(htmlentities($_REQUEST['message']));
			$v->setMsg('Message sent to '.htmlentities($_REQUEST['number']));
		}
		$v->run();
	}
}
