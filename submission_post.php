<?php

require 'includes/aws.phar';

// aws setup stuff
use Aws\S3\S3Client;

use Aws\Exception\AwsException;

// start session if its not already started
if(!isset($_SESSION)) session_start();

// array to store all validation errors in
$errs = [];

// confirm the user is logged in
if (!isset($_SESSION["username"])) {
	$errs[] = "You must be logged in to submit a station.";
}

// check that all required parameters were sent as POST
if (empty($_POST["obj_name"]) || empty($_POST["obj_type"]) || empty($_POST["obj_lat"]) || empty($_POST["obj_long"])) {
	$errs[] = "Form data is incomplete, missing at least one of: station name, type, latitude, or longitude.";
}

// confirm that type is valid
$valid_types = array("Train", "Bus", "Tram", "Subway", "Ferry");
if (!in_array($_POST["obj_type"], $valid_types)) {
	$errs[] = "Invalid type provided for this station.";
}

// we have some size constraints in the db, validate entry meets these constraints
if (!empty($_POST["obj_name"]) && strlen($_POST["obj_name"]) > 60) {
	$errs[] = "Station name is too long, please ensure it is less than 60 characters long.";
}

if (!empty($_POST["obj_desc"]) && strlen($_POST["obj_desc"]) > 100) {
	$errs[] = "Station description is too long, please ensure it is less than 100 characters long.";
}

if (!empty($_POST["obj_city"]) && strlen($_POST["obj_city"]) > 30) {
	$errs[] = "Station city is too long, please ensure it is less than 30 characters long.";
}

if (!empty($_POST["obj_url"]) && strlen($_POST["obj_url"]) > 100) {
	$errs[] = "Station url is too long, please ensure it is less than 100 characters long.";
}

// TODO: validate latitude and longitude. do any necessary validations on image and video.
if (!is_numeric($_POST["obj_lat"]) || !is_numeric($_POST["obj_long"])) {
	$errs[] = "Object latitude or longitude was not provided as a numeric value.";
}

// only proceed if no validation errors were caught
if (count($errs) === 0) {
	// get the variables for database connection
	include('includes/db.php');

	try {
		// establish connection
		$pdo = new PDO($dsn, $user, $pass, $options);

		// load the user's account ID to be inserted as foreign key
		$id_query = $pdo->prepare("SELECT id FROM users WHERE username = ?");
		$id_query->execute([$_SESSION["username"]]);
		$user_id = $id_query->fetch(PDO::FETCH_OBJ);

		if (!isset($user_id->id)) {
			$errs[] = "Something went wrong while retrieving your profile from the database.";
		} else {
			// TODO: validate that this object doesn't already exist.
			// realistically though this is impossible, outside of checking latitude/longitude.
			// and this is still not a good method since minor changes to lat/long will still be
			// roughly the same location, but will slip through the validation.

			// The same options that can be provided to a specific client constructor can also be supplied to the Aws\Sdk class.
			// I set up the bucket in ca-central-1
			$sharedConfig = [
			    'region' => 'ca-central-1',
			    'version' => 'latest'
			];

			// Create an SDK class used to share configuration across clients.
			$sdk = new Aws\Sdk($sharedConfig);

			// Create an Amazon S3 client using the shared configuration data.
			$s3Client = $sdk->createS3();

			$key = '';
			$key_vid = '';

			// if image uploaded, store the image
			if (isset($_FILES["obj_img"]) && !empty($_FILES["obj_img"]) && $_FILES["obj_img"]["error"] !== 4) {

				// create a unique key to store the object into s3 with
				$ext = pathinfo($_FILES["obj_img"]["name"], PATHINFO_EXTENSION);
				$key = uniqid($_SESSION["username"], true) . "." . $ext;

				// Send a PutObject request and get the result object.
				$result = $s3Client->putObject([
				    'Bucket' => 'transitrating.tk-uploads',
				    'Key' => $key,
				    'Body' => file_get_contents($_FILES['obj_img']['tmp_name']),
				    'ACL'    => 'public-read'
				]);
			}

			// if video uploaded, store the image
			if (isset($_POST["obj_vid"]) && !empty($_POST["obj_vid"]) && $_FILES["obj_vid"]["error"] !== 4) {

				// do it again for the video
				$ext_vid = pathinfo($_FILES["obj_vid"]["name"], PATHINFO_EXTENSION);
				$key_vid = uniqid($_SESSION["username"], true) . "." . $ext_vid;

				// Send a PutObject request and get the result object.
				$result = $s3Client->putObject([
				    'Bucket' => 'transitrating.tk-uploads',
				    'Key' => $key_vid,
				    'Body' => file_get_contents($_FILES['obj_vid']['tmp_name']),
				    'ACL'    => 'public-read'
				]);
			}

			// collect variables to use in the query
			$name = $_POST["obj_name"];
			$type = $_POST["obj_type"];
			$lat = $_POST["obj_lat"];
			$long = $_POST["obj_long"];

			// escape lt/gt characters
			$name = str_replace('<', '&lt;', $name);
			$name = str_replace('>', '&gt;', $name);

			$type = str_replace('<', '&lt;', $type);
			$type = str_replace('>', '&gt;', $type);

			// lat/long are already validated w. is_numeric so they must not contain lt/gt

			$desc = "";
			if (isset($_POST["obj_desc"])) $desc = $_POST["obj_desc"];

			$city = "";
			if (isset($_POST["obj_city"])) $city = $_POST["obj_city"];

			$province = "";
			if (isset($_POST["obj_province"])) $province = $_POST["obj_province"];

			$url = "";
			if (isset($_POST["obj_url"])) $url = $_POST["obj_url"];

			$desc = str_replace('<', '&lt;', $desc);
			$desc = str_replace('>', '&gt;', $desc);

			$city = str_replace('<', '&lt;', $city);
			$city = str_replace('>', '&gt;', $city);

			$province = str_replace('<', '&lt;', $province);
			$province = str_replace('>', '&gt;', $province);

			$url = str_replace('<', '&lt;', $url);
			$url = str_replace('>', '&gt;', $url);

			// insert into the database
			$pdo->prepare("INSERT INTO stations (`name`, `desc`, `type`, latitude, longitude, city, province, url, image, video, uploader) VALUES (?,?,?,?,?,?,?,?,?,?,?)")->execute([$name,$desc,$type,$lat,$long,$city,$province,$url,$key,$key_vid,$user_id->id]);
			$sid = $pdo->lastInsertId();
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
		$page_id = 'submit';
		include("includes/navbar.php"); 
	?>

	<?php include("includes/login-modal.php"); ?>

	<!-- Main page content -->
	<section>
		<?php
			// if any errors occurred, list them for the user.
			// otherwise, report that registration was successful.
			if (count($errs) > 0) {
				echo "<p>Submission was unsuccessful. Please review the errors below.</p>";
				foreach ($errs as $err) {
					echo "<p>".$err."</p>";
				}
				echo "<p>Click <a href=\"submission.php\">here</a> to retry submission.</p>";
			} else {
				echo "<p>Submission completed successfully. Click <a href=\"station.php?sid=".$sid."\">here</a> to view your submission.</p>";
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