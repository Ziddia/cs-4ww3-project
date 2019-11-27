<?php

// array to store all validation errors in
$errs = [];
$user = '';

// start session if its not already started
if(!isset($_SESSION)) session_start();

// confirm the user is logged in
if (!isset($_SESSION["username"])) {
	$errs[] = "You must be logged in to submit a review.";
}

// check that all required parameters were sent as POST
if (empty($_POST["station"]) || empty($_POST["rating"])) {
	$errs[] = "Form data is incomplete, missing at least one of: station ID, rating.";
}

// validate rating within 0 - 5
if ($_POST["rating"] < 0 || $_POST["rating"] > 5) {
	$errs[] = "Rating is invalid, it must be between 0 and 5.";
}

// escape gt/lt characters
$review = str_replace('<', '&lt;', $_POST["review"]);
$review = str_replace('>', '&gt;', $review);

// only proceed if no validation errors were caught
if (count($errs) === 0) {
	// get the variables for database connection
	include('includes/db.php');

	try {
		// establish connection
		$pdo = new PDO($dsn, $user, $pass, $options);

		// check if the station requested exists
		$stationExists = $pdo->prepare("SELECT * FROM stations WHERE id = ?");
		$stationExists->execute([$_POST["station"]]);
		if (!($stationExists->fetchColumn())) {
			$errs[] = "The station provided for the review does not exist.";
		} else {
			// get the user's ID
			$user = $_SESSION["username"];
			$id_query = $pdo->prepare("SELECT id FROM users WHERE username = ?");
			$id_query->execute([$_SESSION["username"]]);
			$user_id = $id_query->fetch(PDO::FETCH_OBJ);
			// confirm user ID was fetched properly
			if (!isset($user_id->id)) {
				$errs[] = "Something went wrong while retrieving your profile from the database.";
			} else {
				// inserts the new rating into the database
				$pdo->prepare("INSERT INTO comments (author, station, rating, text) VALUES (?,?,?,?)")->execute([$user_id->id, $_POST["station"], $_POST["rating"], $review]);
			}
		}
	} catch (\PDOException $e) {
		$errs[] = "An error occurred while communicating with the database.";
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
}

// return data
$return = array('errors' => $errs, 'success' => (count($errs) === 0), 'user' => $user);
echo json_encode($return);

?>