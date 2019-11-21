<!DOCTYPE html>
<html lang="en">

<head>
	<title>Transit Rating - Sample Results</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="static/base.css">
	<link rel="stylesheet" type="text/css" href="static/results_sample.css">
	<link rel="stylesheet" type="text/css" href="static/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="static/results_sample.css">
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
							<th scope="col">#</th>
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
						<tr>
							<th scope="row">1</th>
							<td>0/5</td>
							<td>Union Subway</td>
							<td>Subway</td>
							<td>43.6452° N, 79.3806° W</td>
							<td>Toronto</td>
							<td>ON</td>
							<!-- these temp links are just here to provide a way to get to the individual sample page 
								once javascript/data is added users will be able to click on a row and go to the corresponding 
								individual page-->
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
						</tr>
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

	<script src="static/results_sample.js"></script>

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