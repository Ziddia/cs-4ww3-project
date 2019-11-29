// variable to store the map object coming from google maps API
var map;
// reference to places service
var service;


var latitude;
var longitude;

function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(showPosition);
	} else {
		alert("Geolocation is not supported by this broswer.");
	}
}

function showPosition(position) {
	$("#lat").val(position.coords.latitude);
	$("#long").val(position.coords.longitude);
	console.log($("#lat") + " " + $("#long"));
	//latitude = position.coords.latitude;
	//longitude = position.coords.longitude;
	//$("#location_results").html("<p class=\"my-2\">Search by Latitude: " + latitude + ", Longitude: " + longitude + "</p>")
}

function validate() {
	// grab contents of the form by their IDS, with jquery
	var search = $("#search").val();
	var rating = $("#rating").val();
	console.log(search);
	// check if search is greater than 100 characters
	if (search.length > 100) {
		$("#form_error").html("<p>Search cannot be more than 100 characters long.</p>");
		return false;
	}

	// check if rating is within 0 <= rating <= 6
	// this should never raise an error because the input is a list of options
	if (!(0 <= rating) || !(rating <= 6)) {
		$("#form_error").html("<p>Rating must be between 0 and 6 inclusive.</p>");
		return false;
	}

	// if all checks passed we return true and this allows the for to submit
	return true;
}