<!DOCTYPE html>
<html lang="en">

<head>
	<title>Transit Rating - Search for Location</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
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

	<!-- Main page content -->
	<section>
		<h3>Search Transit Stations</h3>
		<form action="search_results.php" method="GET" class="header-buffer" onsubmit="return validate();">
			<div class="form-group">
				<label for="search">Keyword Search</label>
				<input type="text" class="form-control" name="search" id="search">
			</div>
			<div class="form-group">
				<label for="rating" class="col-form-label">Rating</label>
				<select class="form-control" name="rating" id="rating">
					<option>0</option>
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
				</select>
			</div>
			<div class="form-group">
				<label for="lat" class="col-form-label">Latitude</label>
				<input type="number" class="form-control" name="lat" id="lat" step="any">
			</div>
			<div class="form-group">
				<label for="long" class="col-form-label">Longitude</label>
				<input type="number" class="form-control" name="long" id="long" step="any"></input>
			</div>
			<div class="form-group">
				<button type="button" class="btn btn-secondary col-md-2" onclick="getLocation()">Search Near Me</button>
				<!--<div id="location_results"></div>-->
			</div>
			<div id="form_error"></div>
			<button type="submit" class="btn btn-secondary">Search</button>
			
		</form>
	</section>

	<div class="modal" id="modal_location" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Location Selection</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="map" id="map"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary">Save Changes</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<footer>
		<h5>Transit Rating</h5>
		<h6>Helping You Get Places</h6>
	</footer>

	<script src="static/search.js"></script>

	<!-- jQuery/Bootstrap scripts -->
	<script
	  src="https://code.jquery.com/jquery-3.4.1.min.js"
	  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	  crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<!-- Google Maps API -->
	<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAW0YtpSWfJsdHE5dxZtwUx2TBhOlnM0r0&libraries=places&callback=placesInit"
    async defer></script>-->
</body>

</html>