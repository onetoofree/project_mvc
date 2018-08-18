
      function initAutocomplete() {
          // var myLatlng = {lat: 51.5074, lng: 0.1278};
          var myLatlng = {lat: 51.5219, lng: -0.1302};
          var map = new google.maps.Map(document.getElementById("map"), {
          center: myLatlng,
          zoom: 13,
          mapTypeId: "roadmap"
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById("pac-input");
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current maps viewport.
        map.addListener("bounds_changed", function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener("places_changed", function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

        
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
              //console.log(position);
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });

        map.addListener("click", function(e) {
            
            placeMarkerAndPanTo(e.latLng, map);
            post(e.latLng, map);
            // var coordinates = getCoords(e.latLng, map);
            // var latitude = coordinates.lat();
            // var longitude = coordinates.lng();
            // alert("coords are: " + latitude + " and: " + longitude);
  });

 
function post(coords, map){
    var coordinates = getCoords(coords, map);
    var latitude = coordinates.lat();
    var longitude = coordinates.lng();
    $.post("submissionForm.php", {postlat:latitude, postlng:longitude},
    function(data)
    {
        $("#result").html(data);
    }
   );
}

function placeMarkerAndPanTo(latLng, map) {
  var marker = new google.maps.Marker({
    position: latLng,
    map: map
  });
  map.panTo(latLng);
}

function getCoords(latLng, map) {
  var marker = new google.maps.Marker({
    position: latLng,
    map: map
  });
  return latLng;

}
    }