// variable to store the map object coming from google maps API
var map;
var service;

function LoadLocation(position) {
	var latitude = position.coords.latitude;
	var longitude = position.coords.longitude;
	var location = new google.maps.LatLng(latitude, longitude);
	map.setCenter(location);

	var request = {
		location: location,
		radius: '30000',
		type: 'transit_station'
	};

	service.nearbySearch(request, function(results, status) {
		console.log(results);
		console.log(status);
	});
}

function placesInit() {
	// creates a map, since transit lines are quite large zoom of 8 (roughly city level) seems appropriate
	map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8
        });

	service = new google.maps.places.PlacesService(map);
}