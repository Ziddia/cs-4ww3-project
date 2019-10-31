// variable to store the map object coming from google maps API
var map;
// variable to show information when user clicks on a marker on the map
var infowindow;

// utility function to place a marker on the map.
// this function comes from the Places API:
// https://developers-dot-devsite-v2-prod.appspot.com/maps/documentation/javascript/examples/place-search
function createMarker(place) {
  // create a new marker and position it on the map
	var marker = new google.maps.Marker({
		map: map,
		position: place.geometry.location
	});

  // adds an event listener which will show a popup window when the marker is clicked
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(place.name);
		infowindow.open(map, this);
	});
}

// initializes the map, pointing at the location of the object.
// this function will eventually need to be dynamic on the object's location
// but for now can be static.
// also sourced from the Places API documentation.
// https://developers-dot-devsite-v2-prod.appspot.com/maps/documentation/javascript/examples/place-search
function initMap() {
  // initializes the info popup
	infowindow = new google.maps.InfoWindow();

  // creates a map, since transit lines are quite large zoom of 8 (roughly city level) seems appropriate
	map = new google.maps.Map(document.getElementById('map'), {
          zoom: 15
        });

  // the place we are searching for, this part will need to be dynamic eventually.
	var request = {
      query: 'EMERSON at ROYAL',
      fields: ['name', 'geometry'],
    };

    // gets the places API service
    service = new google.maps.places.PlacesService(map);

    // search for the specified object and create markers for all results.
    service.findPlaceFromQuery(request, function(results, status) {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
        for (var i = 0; i < results.length; i++) {
          createMarker(results[i]);
        }

        map.setCenter(results[0].geometry.location);
      }
    });
}