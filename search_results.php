<?php

require 'includes/aws.phar';

// don't know if I need this
// aws setup stuff
use Aws\S3\S3Client;

use Aws\Exception\AwsException;

// start session if it is not already started
if(!isset($_SESSION)) session_start();

// array to store all validation errors in
$errs = [];

// make sure the rating is between 0 and 5 inclusive
if ($_GET["rating"] < 0 || $_GET["rating"] > 5) {
	$errs[] = "Invalid ratting provided.";
}

// we have some size constraints in the db, validate search meets these constraints
if (!empty($_GET["search"]) && strlen($_GET["search"]) > 100) {
	$errs[] = "Search term is too long, please ensure it is less than 100 characters long.";
}

if (!is_numeric($_GET["lat"]) || !is_numeric($_GET["long"])) {
	$errs[] = "Latitude and Longitude must be numeric values.";
}

// user must specify both lat and long
if ((empty($lat) && !empty($long)) || (!empty($lat) && empty($long))) {
	$errs[] = "Must specify Latitude and Longitude.";
}

if (count($errs) === 0) {
	// get the variables for database connection
	include('includes/db.php');

	try {
		// establish connection
		$pdo = new PDO($dsn, $user, $pass, $options);

		// set to lowercase so that the search is case insensitive
		$search = strtolower($_GET["search"]);
		$rating = $_GET["rating"];
		$lat = $_GET["lat"];
		$long = $_GET["long"];
		if (empty($lat) && empty($long)) {
			$lat = 0;
			$long = 0;
		}

		echo "ECHO lat long <br>";
		echo $lat." ".$long."<br>";

		// escape lt/gt characters
		$search = str_replace('<', '&lt;', $search);
		$search = str_replace('>', '&gt;', $search);

		// lat/long are already validated w. is_numeric so they must not contain lt/gt or any special characters
		echo "CHECK IF SHIts EMPTY";
		if (empty($lat) && empty($long)) {
			$lat = 0.0;
			$long = 0.0;
			echo "SHITS EMPTY";
			// query the database without check for lat/long
			$sql = "SELECT id, rating, `name`, `type`, latitude, longitude, city, province FROM stations INNER JOIN (SELECT comments.station, ROUND(Avg(comments.rating), 1) as rating FROM comments INNER JOIN stations ON stations.id=comments.station GROUP BY comments.station) AS station_ratings ON stations.id=station_ratings.station WHERE (LOWER(`name`) like \"%".$search."%\" OR LOWER(`type`) like \"%".$search."%\" OR LOWER(city) like \"%".$search."%\" OR LOWER(province) like \"%".$search."%\") AND (rating >= ".$rating.".) GROUP BY stations.id";

		}
		else {
			echo "SHTIS NOT EMPTY";
			// query the database
			$sql = "SELECT id, rating, `name`, `type`, latitude, longitude, city, province FROM stations INNER JOIN (SELECT comments.station, ROUND(Avg(comments.rating), 1) as rating FROM comments INNER JOIN stations ON stations.id=comments.station GROUP BY comments.station) AS station_ratings ON stations.id=station_ratings.station WHERE (LOWER(`name`) like \"%".$search."%\" OR LOWER(`type`) like \"%".$search."%\" OR LOWER(city) like \"%".$search."%\" OR LOWER(province) like \"%".$search."%\") AND ((latitude < ".$lat."+0.25 AND latitude > ".$lat."-0.25) OR (longitude < ".$long."+0.25 AND longitude > ".$long."-0.25)) AND (rating >= ".$rating.") GROUP BY stations.id";
		}

		foreach ($pdo->query($sql) as $row) {
			echo $row['id'] . "\t";
			echo $row['rating'] . "\t";
			echo $row['name'] . "\t";
			echo $row['type'] . "\t";
			echo $row['latitude'] . "\t";
			echo $row['longitude'] . "\t";
			echo $row['city'] . "\t";
			echo $row['province'] . "\t";
		}
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
	<title>Transit Rating - Sample Results</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="static/base.css">
	<link rel="stylesheet" type="text/css" href="static/search_results.css">
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

	<!-- Main page content -->
	<section>
		<div class="table-div p-3">
			<h3>Search Results</h3>
			<div>
				<table class="table table-striped table-light table-hover m-0">
					<thead>
						<tr>
							<th scope="col">ID</th>
							<th scope="col">Rating</th>
							<th scope="col">Name</th>
							<th scope="col">Type</th>
							<th scope="col">Lat/Long</th>
							<th scope="col">City</th>
							<th scope="col">Province</th>
							<th scope="col">Temp Link</th>
						</tr>
					</thead>
					<tbody>

						<?php
							foreach ($pdo->query($sql) as $row){
							echo "<tr>";
							echo "<th scope=\"row\">".$row['id']."</th>";
							echo "<td>".$row['rating']."/5</td>";
							echo "<td>".$row['name']."</td>";
							echo "<td>".$row['type']."</td>";
							echo "<td>".$row['latitude']."° N, ".$row['longitude']."° W</td>";
							echo "<td>".$row['city']."</td>";
							echo "<td>".$row['province']."</td>";
							echo "<td><a href=\"station.php?sid=".$row["id"]."\">Station Page</a></td>";
							echo "</tr>";
							}
						?>
						<!--<tr>
							<th scope="row">1</th>
							<td>0/5</td>
							<td>Union Subway</td>
							<td>Subway</td>
							<td>43.6452° N, 79.3806° W</td>
							<td>Toronto</td>
							<td>ON</td>
							 these temp links are just here to provide a way to get to the individual sample page 
								once javascript/data is added users will be able to click on a row and go to the corresponding 
								individual page
							<td><a href="individual_sample.html">individual sample</a></td>
						</tr>
						<tr>
							<th scope="row">2</th>
							<td>0/5</td>
							<td>Queen St West At Ossington Ave</td>
							<td>Tram</td>
							<td>43.6443° N, 79.4187° W</td>
							<td>Toronto</td>
							<td>ON</td>
							<td><a href="individual_sample.html">individual sample</a></td>
						</tr>
						<tr>
							<th scope="row">3</th>
							<td>3/5</td>
							<td>EMERSON at ROYAL</td>
							<td>Bus</td>
							<td>43.2533° N, 79.9216° W</td>
							<td>Hamilton</td>
							<td>ON</td>
							<td><a href="individual_sample.html">individual sample</a></td>
						</tr>-->
					</tbody>
				</table>
			</div>
		</div>

		<div class="px-3 pb-3 map-div">
			<h5>Locations</h5>
			<div id="map" class="map"></div>			
		</div>
	</section>

	<footer>
		<h5>Transit Rating</h5>
		<h6>Helping You Get Places</h6>
	</footer>

	<script src="static/search_results.js"></script>

	<!-- jQuery/Bootstrap scripts -->
	<script
	  src="https://code.jquery.com/jquery-3.4.1.min.js"
	  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	  crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<!-- Google Maps API -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAW0YtpSWfJsdHE5dxZtwUx2TBhOlnM0r0&callback=initMap&libraries=places"
    async defer></script>
</body>

</html>