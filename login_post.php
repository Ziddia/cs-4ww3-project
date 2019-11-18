<?php

// array to store all errors in
$errs = [];

// check that all required parameters were sent as POST
if (empty($_POST["username"]) || empty($_POST["password"])) {
	$errs[] = "Form data is incomplete, missing at least one of: username, password.";
}

// only proceed if no validation errors were caught
if (count($errs) === 0) {
	// get the variables for database connection
	include('includes/db.php');

	try {
		// establish connection
		$pdo = new PDO($dsn, $user, $pass, $options);

		// retrieve password hash for username from the database
		$hash_query = $pdo->prepare("SELECT hash FROM users WHERE username = ?");
		$hash_query->execute([$_POST["username"]]);
		$hash = $hash_query->fetch(PDO::FETCH_OBJ);
		if (!isset($hash->hash)) {
			$errs[] = "Failed to log in, username or password is not valid.a";
		} else {
			// verify the hash
			$valid = password_verify($_POST["password"], $hash->hash);
			if (!$valid) {
				$errs[] = "Failed to log in, username or password is not valid.";
			} else {
				// initiate a session
				if(!isset($_SESSION)) session_start();
				// set the session variable
				$_SESSION["username"] = $_POST["username"];
			}
		}
	} catch (\PDOException $e) {
		$errs[] = "An error occurred while communicating with the database.";
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
}

if (count($errs) !== 0) {
	echo var_dump($errs);
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
		$page_id = '';
		include("includes/navbar.php"); 
	?>

	<?php include("includes/login-modal.php"); ?>

	<!-- Main page content -->
	<section>
		<?php
			// if any errors occurred, list them for the user.
			// otherwise, report that registration was successful.
			if (count($errs) > 0) {
				echo "<p>Login was unsuccessful. Please review the errors below.</p>";
				foreach ($errs as $err) {
					echo "<p>".$err."</p>";
				}
			} else {
				echo "<p>Logged in successfully, please proceed to use the site.</p>";
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