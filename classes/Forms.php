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
			window.location('/');
		}).fail(function (xhr, textStatus, errorThrown) {
			$("#errorMsg").html('<span class="text-danger">' + xhr.responseText + "</span>");
			$("#errorMsg").show();
		});
	}
});
JAVASCRIPT;

	}
}
