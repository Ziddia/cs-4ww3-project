// function to submit a new review to the current page
function ajaxSubmitReview(station) {
  var rating = $("#rating").val();
  var review = $("#review").val();

  $.post('review_post.php', {station: station, rating: rating, review: review}).done(function (data) {
        var js = JSON.parse(data);
        if (js.success) {
          $("#all_comments").prepend(`
            <div class="row opinion-pane">
                <div class="col-12">
                  <div class="row">
                    <div class="col-md-7">
                      @` + js.user + `
                    </div>
                    <div class="col-md-5">
                      Rates this line ` + rating + `/5
                    </div>
                  </div>
                  <!-- Second row has their comments. -->
                  <div class="row">
                    <div class="col-10 offset-1">
                      <p>` + review.replace(/</g, '&lt;').replace(/>/g, '&gt;') + `</p>
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
            `);
        } else {
          for (var i = 0; i < js.errors.length; i++) {
            var err = js.errors[i];
            $("#errors").append("<p>" + err + "</p>");
          }
        }
    });;
}

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