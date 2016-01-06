<?php
class SmsView extends View {

	private $msg = '';	// completion message
	
	public function __construct( $title = 'Send and SMS message using Twilio' ) {
		$this->setNavBarActive('examples');
		parent::__construct( $title );
	}

	public function setMsg($msg) {
		$this->msg = $msg;
	}

	/**
	 * Generate the middle section of the home page
	 */
	protected function getMainSection() {

		$msg = '';
		if (!empty($this->msg)) {
			$msg =<<<HTML
<div class="row">
  <div class="col-md-offset-2 col-md-8">
	<h3 class="text-success">{$this->msg}</h3>
  </div>
</div>
HTML;
		}

		$number = htmlentities($_REQUEST['number']);
		$message = htmlentities($_REQUEST['message']);

		return <<<HTML
<div class="container theme-showcase" role="main">
  <div class="jumbotron">
	<h1>Send an SMS Message</h1>
	<p>
	This example uses the Twilio REST API to send an SMS message.
	</p>
  </div>
  $msg
  <div class="row">
	<form method="POST" class="form-horizontal" role="form" action="/sms">
	<div class="form-group">
		<label for="number" class="col-md-2 control-label">Send to: </label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="number" placeholder="Enter an SMS-capable phone number" value="$number"/>
		</div>
	</div>
	<div class="form-group">
		<label for="message" class="col-md-2 control-label">Message: </label>
		<div class="col-md-8">
			<input type="text" class="form-control" name="message" placeholder="Enter up to 140 characters" value="$message"/>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-offset-2 col-md-2">
			<button type="submit" class="btn btn-primary">Send</button>
		</div>
	</div>
    </form>
  </div>
</div>
HTML;
	}
}
