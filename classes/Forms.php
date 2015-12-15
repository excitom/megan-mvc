<?php
class Forms {
	public static function getLoginForm( $nickName ) {

		return <<<HTML
		<p><span id="errorMsg" class="text-danger"></span></p>
        <form id="loginForm" class="form-inline">
          <div class="form-group">
            <label class="sr-only" for="nn">User Name</label>
            <input type="text" class="form-control" name="nn" id="nn" placeholder="User Name" value="$nickName">
          </div>
          <div class="form-group">
            <label class="sr-only" for="pw">Password</label>
            <input type="password" class="form-control" placeholder="Your Password" name="pw" id="pw">
          </div>
            <button type="button" class="btn btn-primary" id="loginBtn">Sign In</button>
        </form>
		<p><a href="/register">Sign up for a new name</a></p>
HTML;
	}

	public static function getLoginFormJs() {
		return <<<JAVASCRIPT
$('#loginBtn').on('click', function () {
	$('#errorMsg').hide();

	// form validation
	var ok = true;
	var nickName = $('#nn').val();
	if (nickName == '') {
		$('#errorMsg').html('Please enter a user name');
		$('#errorMsg').show();
		ok = false;
	}

	var password = $('#pw').val();
	if (password == '') {
		$('#errorMsg').html('Please enter a password');
		$('#errorMsg').show();
		ok = false;
	}

	// if validation succeeded, try to log in the visitor
	if (ok) {
		$.ajax({
			type: "POST",
			url: "/AjaxLogin",
			data: $("#loginForm").serialize()
		}).done(function (data) {
			window.location = '/';
		}).fail(function (xhr, textStatus, errorThrown) {
			$("#errorMsg").html('<span class="text-danger">' + xhr.responseText + "</span>");
			$("#errorMsg").show();
		});
	}
});
JAVASCRIPT;

	}

	/**
	 * Generate the form inside the registration modal window
	 */
	public static function getRegisterForm() {

		return <<<HTML
	  <h4 id="modalMsg"></h4>
      <div class="modal-body">
        <form id="regForm">
          <div class="form-group">
            <label for="nickName">
	        User Name
	        <span id="nickNameErr" class="text-danger"></span>
	        </label>
            <input name="nickName" type="text" class="form-control" id="nickName" placeholder="Choose a user name">
          </div>
          <div class="form-group">
            <label for="email">
	        Email address
	        <span id="emailErr" class="text-danger"></span>
	        </label>
            <input name="email" type="email" class="form-control" id="email" placeholder="Your email address">
          </div>
          <div class="form-group">
            <label for="password">
	        Password
	        <span id="passwordErr" class="text-danger"></span>
	        </label>
            <input name="password" type="password" class="form-control" id="password" placeholder="Choose a Password">
          </div>
          <div class="form-group">
            <label for="firstName">First Name <em>(optional)</em></label>
            <input name="firstName" type="text" class="form-control" id="firstName" placeholder="Your First Name">
          </div>
          <div class="form-group">
            <label for="lastName">Last Name <em>(optional)</em></label>
            <input name="lastName" type="text" class="form-control" id="lastName" placeholder="Your Last Name">
          </div>
        </form>
      </div>
      <div class="modal-footer">
	    <a href="#" role="button" id="loginLink">I am already registered</a>
        <button type="button" class="btn btn-primary" id="registerBtn">Register Now</button>
        <button type="button" class="btn btn-success" id="doneBtn" style="display: none;">Done!</button>
      </div>
HTML;

	}

	/**
	 * Generate the javascript for the registration modal window
	 */
	public static function getRegisterJs( $returnUrl = null ) {

		if ($returnUrl === null) {
			$doneAction = 'location.reload();';
		} else {
			$doneAction = "window.location = '$returnUrl'";
		}
	
		return <<<JAVASCRIPT
$('#registerBtn').on('click', function () {
	var ok = true;
	// default: clear error messages
	$('#nickNameErr').hide();
	$('#emailErr').hide();
	$('#passwordErr').hide();
	$('#modalMsg').hide();

	// test patterns for field validation
	var nameReg = /^[A-Za-z][A-Za-z0-9_-]+$/;
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

	// form validation
	var nickName = $('#nickName').val();
	if (nickName == '') {
		$('#nickNameErr').html('Please choose a nickname');
		$('#nickNameErr').show();
		ok = false;
	}
	else if (!nameReg.test(nickName)) {
		$('#nickNameErr').html('Please use only letters or numbers and start with a letter');
		$('#nickNameErr').show();
		ok = false;
	}

	var email = $('#email').val();
	if (email == '') {
		$('#emailErr').html('Please provide your email address');
		$('#emailErr').show();
		ok = false;
	}
	else if (!emailReg.test(email)) {
		$('#emailErr').html('Please provide a valid email address');
		$('#emailErr').show();
		ok = false;
	}

	var password = $('#password').val();
	if (password == '') {
		$('#passwordErr').html('Please select a password');
		$('#passwordErr').show();
		ok = false;
	}

	// if validation succeeded, try to register the visitor
	if (ok) {
		$.ajax({
			type: "POST",
			url: "/AjaxRegister",
			data: $("#regForm").serialize()
		}).done(function (data) {
			$("#modalMsg").html('<span class="text-success">' + data + "</span>");
			$("#modalMsg").show();
			$("#registerBtn").hide();
			$("#loginLink").hide();
			$("#regForm").hide();
			$("#doneBtn").show();
		}).fail(function (xhr, textStatus, errorThrown) {
			$("#modalMsg").html('<span class="text-danger">' + xhr.responseText + "</span>");
			$("#modalMsg").show();
		});
	}
});
$('#doneBtn').on('click', function () {
	$doneAction
});
$('#loginLink').on('click', function () {
	window.location = '/login';
});
JAVASCRIPT;
	}
}
