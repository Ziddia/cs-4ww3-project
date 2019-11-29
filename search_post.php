<?php

require 'includes/aws.phar';

// start session if it is not already started
if(!isset($_SESSION)) session_start();

// array to store all validation erros in
$errs = [];

// make sure the rating is between 0 and 5 inclusive
if ($_GET["rating"] < 0 || $_GET["rating"] > 5) {
	$errs[] = "Invalid ratting provided.";
}

// we have some size constraints in the db, validate search meets these constraints
if (!empty($_GET["search"]) && strlen($_GET["search"]) > 100) {
	$errs[] = "Search term is too long, please ensure it is less than 100 characters long.";
}

// MIGHT NOT NEED THIS
// TODO: validate latitude and longitude.
//if (!is_numeric($_GET["lat"]) || !is_numeric($_GET["long"])) {
//	$errs[] = "Latitude and Longitude must be numeric values.";
	//print "<h1>VARIABLES</h1>";
	//print $_GET;
//	print $_GET["lat"];
//	print $_GET["long"];
//}

if (count($errs) === 0) {
	// get the variables for database connection
	include('includes/db.php');

	try {
		// establish connection
		$pdo = new PDO($dsn, $user, $pass, $options);

		$search = $_GET["search"];
		$rating = $_GET["rating"];
		$lat = $_GET["lat"];
		$long = $_GET["long"];

		// escape lt/gt characters
		$search = str_replace('<', '&lt;', $search);
		$search = str_replace('>', '&gt;', $search);

		// lat/long are already validated w. is_numeric so they must not contain lt/gt or any special characters

		// query the database
		// need to add a query for the rating
		//$pdo->prepare("SELECT `name`, `type`, latitude, longitude, city, province, url, FROM stations")->query() as $row;

		$sql = "SELECT `name`, `type`, latitude, longitude, city, province FROM stations";
		foreach ($pdo->query($sql) as $row) {
			print $row['name'] . "\t";
			print $row['type'] . "\t";
			print $row['latitude'] . "\t";
			print $row['longitude'] . "\t";
			print $row['city'] . "\t";
			print $row['province'] . "\t";
		}
		// should go to another page with the search results.
		// possibly before the call to the database has even happened.
		//$sid = $pdo->
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
		$page_id = 'search';
		include("includes/navbar.php");
	?>

	<?php include("includes/login-modal.php"); ?>

	<!-- Main Page content	-->
	<section>
		<?php
			// if any errors occured, list them for the user.
			// otherwise, display the search results
			if (count($errs) > 0) {
				echo "<p>Something went wrong with the search. Please review the errors below.</p>";
				foreach ($errs as $err) {
					echo "<p>".$err."</p>";
				}
				echo "<p>Click <a href=\"search.php\">here</a> to retry search.</p>";
			} else {
				echo "<p>Search completed successfully. Click <a href=\"#\">here</a> to view your search.</p>";
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