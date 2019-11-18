<!DOCTYPE html>
<html lang="en">

<head>
	<title>Transit Rating - Register for an Account</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
		<h3>Register an Account</h3>
		<!-- This form goes nowhere, but will eventually POST to some form submission (PHP I guess) -->
		<form action="#" method="POST" class="header-buffer" onsubmit="return validate();">
			<div class="form-group row">
				<label for="user_email" class="col-md-2 col-form-label">E-mail Address</label>
				<input type="email" class="form-control col-md-10" id="user_email">
			</div>
			<div class="form-group row">
				<label for="reg_username" class="col-md-2 col-form-label">Username</label>
				<input type="text" class="form-control col-md-10" id="reg_username">
			</div>
			<div class="form-group row">
				<label for="reg_password" class="col-md-2 col-form-label">Password</label>
				<input type="password" class="form-control col-md-10" id="reg_password">
			</div>
			<div class="form-group row">
				<label for="confirm" class="col-md-2 col-form-label">Confirm Password</label>
				<input type="password" class="form-control col-md-10" id="confirm">
			</div>
			<div class="form-check row">
				<input type="checkbox" class="form-check-input" id="subscribe">
				<label class="form-check-label" for="subscribe">Subscribe to update e-mails</label>
			</div>
			<br/>
			<div id="form_error"></div>
			<button type="submit" class="btn btn-secondary">Register</button>
		</form>
	</section>

	<footer>
		<h5>Transit Rating</h5>
		<h6>Helping You Get Places</h6>
	</footer>

	<script src="static/registration.js"></script>

	<!-- jQuery/Bootstrap scripts -->
	<script
	  src="https://code.jquery.com/jquery-3.4.1.min.js"
	  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	  crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>