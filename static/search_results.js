// variable to store the map object coming from google maps API
var map;
// variable to show information when user clicks on a marker on the map
var infowindow;

// get the sql results from the html tag where php placed them
var results = JSON.parse($('#sql_results').text());

// utility function to place a marker on the map.
// this function comes from the Places API:
// https://developers-dot-devsite-v2-prod.appspot.com/maps/documentation/javascript/examples/place-search
function createMarker(place) {

	var latlong = {lat: parseFloat(place.latitude), lng: parseFloat(place.longitude)};

	// create a new marker and postion it on the map
	var marker = new google.maps.Marker({
		map: map,
		position: latlong
	});

	// adds an event listener which will show a popup window when the marker is clicked
	google.maps.event.addListener(marker, 'click', function() {
		// fill the popup dialog with information about the entry
		infowindow.setContent("<h6>" + place.name + "</h6><p>"+place.type+" in "+place.city+" "+place.province+"</p><p><a href=\"station.php?sid="+place.id+"\">Station Page</a></p>");
		infowindow.open(map, this);
	});
}

// initializes the map, pointing at the location of the objects.
// also sourced from the Places API documentation.
// https://developers-dot-devsite-v2-prod.appspot.com/maps/documentation/javascript/examples/place-search
function initMap() {
	// initializes the info popup
	infowindow = new google.maps.InfoWindow();

	// creates a map, since transit lines are quite large zoom of 8 (roughly city level) seems appropriate
	map = new google.maps.Map(document.getElementById('map'), {
			zoom: 8
		});

	for (var i = 0; i < results.length; i ++) {
		createMarker(results[i]);
	}

	var center = {lat: parseFloat(results[0].latitude), lng: parseFloat(results[0].longitude)};
	map.setCenter(center);

	// // hardcoded queries to use for the map markers
	// var queries = ['Union Subway', 'Queen St West At Ossington Ave', 'EMERSON at ROYAL']
	// queries.forEach(function(searchQuery) {
	// 	// the place we are searching for, this part will need to be dynamic eventually.
	// 	var request = {
	// 		query: searchQuery,
	// 		fields: ['name', 'geometry'],
	// 	};

	// 	// gets the places API service
	// 	service = new google.maps.places.PlacesService(map);

	// 	// search for the specified object and create markers for all results
	// 	service.findPlaceFromQuery(request, function(results, status) {
	// 		if (status === google.maps.places.PlacesServiceStatus.OK) {
	// 			for (var i = 0; i < results.length; i++) {
	// 				createMarker(results[i]);
	// 			}

	// 			map.setCenter(results[0].geometry.location);
	// 		}
	// 	});
	// })

}