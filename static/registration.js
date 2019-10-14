function validate() {
	// grab contents of the form by their IDs, with jquery
	var email = $("#user_email").val();
	var username = $("#reg_username").val();
	var password = $("#reg_password").val();
	var confirm = $("#confirm").val();
	var subscribe = $("#subscribe").is(":checked");

	// confirm all fields are filled out
	if (email == "" || username == "" || password == "" || confirm == "") {
		// insert some text to indicate to the user that the form validation failed
		$("#form_error").html("<p>Please ensure you fill out all fields.</p>");
		// return false to prevent the form from submitting
		return false;
	}

	// regex for checking email
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	// confirm email is in a valid format
	if (!re.test(email)) {
		$("#form_error").html("<p>E-mail address is in an invalid format.</p>");
		return false;
	}

	// check password length requirement
	if (password.length < 8) {
		$("#form_error").html("<p>Password must be at least 8 characters long.</p>");
		return false;
	}

	// confirm the password and confirmation match
	if (password != confirm) {
		$("#form_error").html("<p>Password and confirmation do not match.</p>");
		return false;
	}
	
	// if all checks passed we return true and this allows the form to submit
	return true;
}