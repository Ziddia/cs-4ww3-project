<!DOCTYPE html>
<html lang="en">

<head>
	<title>Transit Rating - Station Information</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="static/base.css">
	<!-- using this stylesheet because individual_sample has specific styling on comment boxes -->
	<link rel="stylesheet" type="text/css" href="static/individual.css">
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
		<!-- Header will show both the name and the location -->
		<h3>EMERSON at ROYAL - Hamilton, ON</h3>
		<!-- Average rating over x reviews -->
		<h5>Rated 3/5 by 3 users</h5>
		<!-- If an 'External URL' was provided on object creation it's placed here -->
		<!-- target="_blank" forces this to open in a new tab -->
		<h6><a href="https://www.hamilton.ca/hsr-bus-schedules-fares" target="_blank">External Information</a></h6>
		<!-- Image uploaded by the user to represent this transit system -->
		<div class="row header-buffer align-items-center justify-content-center">
			<!-- Picture tag to select whether to use a resized image or not -->
			<div class="col-md-6">
				<picture>
					<source media="(min-width: 512px)" srcset="static/hsr_uploaded_hr.png" class="img-fluid rounded mx-auto d-block">
					<img src="static/hsr_uploaded.png" alt="HSR Bus Line" class="img-fluid rounded mx-auto d-block">
				</picture>
			</div>
			<div class="col-md-6">
				<!-- Sample video was sourced from https://www.hamiltontransit.ca/new-buses-hit-the-road/ -->
				<video controls>
					<source src="static/hsr_uploaded_video.webm" type="video/webm">
				</video>
			</div>
		</div>
		<!-- Making use of bootstrap grid system here
		Since I use .col-md-* classes these will all render stacked on smaller devices,
		but will be in columns on larger devices - I think this looks best. -->
		<div class="row header-buffer">
			<div class="col-md-6">
				<!-- Comment section -->
				<h5>User Opinion</h5>

				<!-- .opinion-pane gives us a nice-looking comment box. -->
				<div class="row opinion-pane">
					<div class="col-12">
						<!-- Upper row will have username and rating. -->
						<div class="row">
							<div class="col-md-7">
								@IDislikeHSR
							</div>
							<div class="col-md-5">
								Rates this line 0/5
							</div>
						</div>
						<!-- Second row has their comments. -->
						<div class="row">
							<div class="col-10 offset-1">
								<p>bad service, always late, drivers never smile, very disappointed :(</p>
							</div>
						</div>
						<!-- Last row has buttons (like the comment, report abuse) -->
						<div class="row">
							<div class="col-4">
								<a href="#">Report Abuse</a>
							</div>
							<div class="offset-4 col-4">
								<a href="#" class="float-right">Like (3)</a>
							</div>
						</div>
					</div>
				</div>

				<div class="row opinion-pane">
					<div class="col-12">
						<div class="row">
							<div class="col-md-7">
								@HSR_Rulez
							</div>
							<div class="col-md-5">
								Rates this line 5/5
							</div>
						</div>
						<div class="row">
							<div class="col-10 offset-1">
								<p>IDislikeHSR is a big liar, the drivers are extremely friendly and the bus ride is always smooth and pleasant. This stop is always good and the drivers always pick me up.</p>
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<a href="#">Report Abuse</a>
							</div>
							<div class="offset-4 col-4">
								<a href="#" class="float-right">Like (11)</a>
							</div>
						</div>
					</div>
				</div>

				<div class="row opinion-pane">
					<div class="col-12">
						<div class="row">
							<div class="col-md-7">
								@TransitGuru
							</div>
							<div class="col-md-5">
								Rates this line 3/5
							</div>
						</div>
						<div class="row">
							<div class="col-10 offset-1">
								<p>The HSR provides smooth service at a mediocre rate. In comparison to other metropolitan areas, the bus system is not too extensive. They run frequently enough to be useable. Overall, solid choice, but nothing special.</p>
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<a href="#">Report Abuse</a>
							</div>
							<div class="offset-4 col-4">
								<a href="#" class="float-right">Like (3)</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Contains the location of the system on a map.  -->
			<div class="col-md-6 full-height">
				<h5>Location</h5>
				<div id="map" class="map"></div>
			</div>
		</div>
	</section>

	<footer>
		<h5>Transit Rating</h5>
		<h6>Helping You Get Places</h6>
	</footer>

	<script src="static/individual.js"></script>

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