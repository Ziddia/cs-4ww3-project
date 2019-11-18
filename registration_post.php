<?php

// array to store all validation errors in
$errs = [];

// check that all required parameters were sent as POST
if (empty($_POST["reg_password"]) || empty($_POST["confirm"]) || empty($_POST["user_email"]) || empty($_POST["reg_username"])) {
	$errs[] = "Form data is incomplete, missing at least one of: username, email, password, confirmation.";
}

// default value for subscription
$subscribe = false;

// check if the subscription checkbox was passed in post (only happens if it's checked)
if (!empty($_POST["subscribe"])) {
	$subscribe = true;
}

// confirm that password and confirmation match
if (!empty($_POST["reg_password"]) && !empty($_POST["confirm"]) &&  $_POST["reg_password"] !== $_POST["confirm"]) {
	$errs[] = "Password and confirmation do not match.";
}

// confirm that password length is good
if (!empty($_POST["reg_password"]) && strlen($_POST["reg_password"]) < 8) {
	$errs[] = "Password length is less than 8.";
}

// confirm that e-mail address is valid
if (!empty($_POST["user_email"]) && !filter_var($_POST["user_email"], FILTER_VALIDATE_EMAIL)) {
	$errs[] = "Provided e-mail address is invalid.";
}

// only proceed if no validation errors were caught
if (count($errs) === 0) {
	// hash the password using bcrypt
	$hash = password_hash($_POST["reg_password"], PASSWORD_DEFAULT);

	// get the variables for database connection
	include('includes/db.php');

	try {
		// establish connection
		$pdo = new PDO($dsn, $user, $pass, $options);

		// check if the username or email is already in use
		$userExists = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
		$userExists->execute([$_POST["reg_username"], $_POST["user_email"]]);
		if ($userExists->fetchColumn()) {
			$errs[] = "Provided e-mail address or username is already in use.";
		} else {
			// inserts the new user into the database
			$pdo->prepare("INSERT INTO users (username, hash, email, subscribe) VALUES (?,?,?,?)")->execute([$_POST["reg_username"], $hash, $_POST["user_email"], $subscribe]);
		}
	} catch (\PDOException $e) {
		$errs[] = "An error occurred while communicating with the database.";
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Transit Rating</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="static/index.css">
	<link rel="stylesheet" type="text/css" href="static/base.css">
	<link rel="stylesheet" type="text/css" href="static/bootstrap/css/bootstrap.min.css">
	<!-- need this sheet for hamburger icon for mobile nav -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
	<?php include("includes/header.php"); ?>
	<?php 
		$page_id = 'reg';
		include("includes/navbar.php"); 
	?>

	<?php include("includes/login-modal.php"); ?>

	<!-- Main page content -->
	<section>
		<?php
			// if any errors occurred, list them for the user.
			// otherwise, report that registration was successful.
			if (count($errs) > 0) {
				echo "<p>Registration was unsuccessful. Please review the errors below.</p>";
				foreach ($errs as $err) {
					echo "<p>".$err."</p>";
				}
				echo "<p>Click <a href=\"registration.php\">here</a> to retry registration.</p>";
			} else {
				echo "<p>Registration completed successfully, you can proceed to log in now.</p>";
			}
		?>
	</section>

	<footer>
		<h5>Transit Rating</h5>
		<h6>Helping You Get Places</h6>
	</footer>

	<!-- jQuery/Bootstrap scripts -->
	<script
	  src="https://code.jquery.com/jquery-3.4.1.min.js"
	  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	  crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>