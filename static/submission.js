// variable to store the map object coming from google maps API
var map;
// reference to places service
var service;
// popup window
var infowindow;
// used to convert lat/long coords into an actual address, or vice versa
var geocoder;

// utility function to place a marker on the map.
// this function comes from the Places API:
// https://developers-dot-devsite-v2-prod.appspot.com/maps/documentation/javascript/examples/place-search
function createMarker(place, id) {
  // create a new marker and position it on the map
	var marker = new google.maps.Marker({
		map: map,
		position: place.geometry.location
	});

  // adds an event listener which will show a popup window when the marker is clicked
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(place.name + "<br/><button type='button' class='btn btn-secondary' onclick='SetLocation("+id+");return false;'>Select</button>");
		infowindow.open(map, this);
	});
}

// this function is called when a marker is selected in the popup modal.
// it decodes the lat/long of the selected location and uses geocoder to fetch address info.
// then it autofills the relevant fields in the form with this info.
function SetLocation(id) {
	// selected location
	var user_location = window.search_results[id];
	$("#obj_lat").val(user_location.geometry.location.lat);
	$("#obj_long").val(user_location.geometry.location.lng);
	// find address from lat/long
    geocoder.geocode({'latLng': user_location.geometry.location}, function(results, status) {
    	for (var c in results[0].address_components) {
    		var comp = results[0].address_components[c];
    		if (comp.types.includes("locality")) {
    			$("#obj_city").val(comp.long_name);
    		}
    		if (comp.types.includes("transit_station")) {
    			$("#obj_name").val(comp.long_name);
    		}
    		if (comp.types.includes("administrative_area_level_1")) {
    			$("#obj_province").val(comp.short_name);
    		}
    	}
    	$("#modal_location").modal('hide');
    });
}

// this loads the user's location and determines the transit stations
// which are nearby to them.
function LoadLocation(position) {
	// user location
	var latitude = position.coords.latitude;
	var longitude = position.coords.longitude;
	// get gmaps object for location
	var location = new google.maps.LatLng(latitude, longitude);
	map.setCenter(location);

	var request = {
		location: location,
		radius: '30000',
		type: 'transit_station'
	};

	// search for nearby transit station objects
	service.nearbySearch(request, function(results, status) {
		if (status === google.maps.places.PlacesServiceStatus.OK) {
			// store results to be used in other functions
			window.search_results = results;
	        for (var i = 0; i < results.length; i++) {
	          createMarker(results[i], i);
	        }
	      }
	});
}

function placesInit() {
	// initializes the info popup
	infowindow = new google.maps.InfoWindow();
	geocoder = new google.maps.Geocoder();
	// creates a map, since transit lines are quite large zoom of 8 (roughly city level) seems appropriate
	map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12
        });

	service = new google.maps.places.PlacesService(map);
}