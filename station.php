<?php

// base URL for AWS uploads
$aws_base = "https://s3.ca-central-1.amazonaws.com/transitrating.tk-uploads/";

// errors loading the station
$errs = [];

// check if a specific station has been requested
if (isset($_GET['sid'])) {
    // get the data for this station from the DB

    // get the variables for database connection
	include('includes/db.php');

	try {
		// establish connection
		$pdo = new PDO($dsn, $user, $pass, $options);

		// retrieve password hash for username from the database
		$query = $pdo->prepare("SELECT * FROM stations WHERE id = ?");
		$query->execute([$_GET['sid']]);
		$station = $query->fetch(PDO::FETCH_OBJ);
		if (!isset($station->id)) {
			// SID is wrong, handle
			$errs[] = "The station ID provided is invalid, you may have followed a bad link.";
		} else {
			// fetch the comments for this station
			$query = $pdo->prepare("SELECT * FROM comments WHERE station = ? ORDER BY id DESC");
			$query->execute([$_GET['sid']]);
			$comments = $query->fetchAll();
			// compute the average score
			$avg_rating = 0;
			foreach ($comments as $comment) {
				$avg_rating += $comment['rating'];
			}
			// make sure to set avg_rating to N/A if no comments
			if (count($comments) !== 0) $avg_rating /= count($comments);
			else $avg_rating = "N/A";

			// we are going to need the usernames of the users that authored comments
			// fetch these and put them in an array mapping ID -> username
			$users = array();
			$user_ids = [];
			foreach ($comments as $comment) {
				$user_ids[] = $comment['author'];
			}

			// query to fetch all author names
			// the str_pad solution for variable length strings is from
			// https://stackoverflow.com/questions/1586587/pdo-binding-values-for-mysql-in-statement
			if (count(array_unique($user_ids)) > 0) {
				$query = $pdo->prepare("SELECT * FROM users WHERE id IN (".str_pad('',count(array_unique($user_ids))*2-1,'?,').")");
				$query->execute(array_unique($user_ids));
				// now we store the user's username in $users
				foreach ($query->fetchAll() as $user) {
					$users[$user['id']] = $user['username'];
				}
			}
		}
	} catch (\PDOException $e) {
		// handle database exceptions
		$errs[] = "An error occurred while communicating with the database.";
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
} else {
    // Fallback behaviour goes here
    $errs[] = "No station ID was provided, you may have followed a bad link.";
}

?>

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
		<?php if (count($errs) === 0) : ?>
			<!-- Header will show both the name and the location -->
			<h3><?php echo $station->name; ?> - <?php echo $station->city; ?>, <?php echo $station->province; ?></h3>
			<!-- Average rating over x reviews -->
			<?php if ($avg_rating !== "N/A") : ?>
				<h5>Rated <?php echo round($avg_rating, 2); ?>/5 by <?php echo count($comments); ?> users</h5>
			<?php endif; ?>
			<!-- If an 'External URL' was provided on object creation it's placed here -->
			<!-- target="_blank" forces this to open in a new tab -->
			<?php if ($station->url !== "") : ?>
				<h6><a href="<?php echo $station->url; ?>" target="_blank">External Information</a></h6>
			<?php endif; ?>
			<!-- Image uploaded by the user to represent this transit system -->
			<div class="row header-buffer align-items-center justify-content-center">
				<!-- Picture tag to select whether to use a resized image or not -->
				<?php if ($station->image !== "") : ?>
					<div class="col-md-6">
						<img src="<?php echo $aws_base . $station->image; ?>" class="img-fluid rounded mx-auto d-block">
					</div>
				<?php endif; ?>
				<?php if ($station->video !== "") : ?>
					<div class="col-md-6">
						<video controls>
							<source src="<?php echo $aws_base . $station->video; ?>" type="video/webm">
						</video>
					</div>
				<?php endif; ?>
			</div>
			<!-- Making use of bootstrap grid system here
			Since I use .col-md-* classes these will all render stacked on smaller devices,
			but will be in columns on larger devices - I think this looks best. -->
			<div class="row header-buffer">
				<div class="col-md-6">
					<!-- Comment section -->
					<h5>User Opinion</h5>

					<?php if(isset($_SESSION["username"])) : ?>
						<!-- .opinion-pane gives us a nice-looking comment box. -->
						<div class="row opinion-pane">
							<div class="col-12">
								<!-- Upper row will have username and rating. -->
								<div class="row">
									<div class="col-md-7">
										@<?php echo $_SESSION["username"]; ?>
									</div>
									<div class="col-md-5">
										Rates this line <input type="number" id="rating" value="3" min="0" max="5">/5
									</div>
								</div>
								<div class="row" style="height:10px"></div>
								<!-- Second row has their comments. -->
								<div class="row">
									<div class="col-10 offset-1">
										<textarea class="form-control" id="review" placeholder="(Optional) your comment here..."></textarea>
									</div>
								</div>
								<div class="row" style="height:10px"></div>
								<div class="row">
									<div class="col-2 offset-8">
										<button type="submit" style="margin-bottom: 1.5vh;" class="btn btn-secondary" onclick="ajaxSubmitReview(<?php echo $_GET['sid']; ?>);">Post</button>
									</div>
								</div>
								<div class="row" id="errors"></div>
							</div>
						</div>
					<?php endif; ?>

					<div id="all_comments">
						<?php foreach($comments as $comment) : ?>
							<!-- .opinion-pane gives us a nice-looking comment box. -->
							<div class="row opinion-pane">
								<div class="col-12">
									<!-- Upper row will have username and rating. -->
									<div class="row">
										<div class="col-md-7">
											@<?php echo $users[$comment['author']]; ?>
										</div>
										<div class="col-md-5">
											Rates this line <?php echo $comment['rating']; ?>/5
										</div>
									</div>
									<!-- Second row has their comments. -->
									<div class="row">
										<div class="col-10 offset-1">
											<p><?php echo $comment['text']; ?></p>
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
						<?php endforeach; ?>
					</div>
				</div>
				<!-- Contains the location of the system on a map.  -->
				<div class="col-md-6 full-height">
					<h5>Location</h5>
					<div id="map" class="map"></div>
				</div>
			</div>
		<?php endif; ?>
		<?php 
			if (count($errs) !== 0) {
				echo "<p>Errors occurred while loading the station information.</p>";
				foreach ($errs as $err) {
					echo "<p>".$err."</p>";
				}
			}
		?>
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