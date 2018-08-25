<?php


    require '../model/searchQueries.php';
    require '../controller/searchFunctions.php';
    require '../model/db_connect.php';
    require '../model/metadataDropDownListQueries.php';
//session_start();

ob_start();

$myArray = performSearch();

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Images</title>
    <link rel = "stylesheet" type = "text/css" href = "lightbox.min.css">
    <script type = "text/javascript" src="lightbox-plus-jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" charset="utf-8"></script>
    <?php include 'css/css.html'; ?>
  </head>
  
  <body>
    <div class="uploadMap">
  <h1>Image Search</h1>
    <input id="pac-input" class="controls" name ="locationSearch" type="text" placeholder="Search Box">
    <div id="map"></div>
    <div id="result"></div>

    
    <script>

        
        var infowindow;
        var latitude = "51.5074";
        var longitude = "0.1278";
        var locationPlaceCoords = [];
        
      function initAutocomplete() {
          var myLatlng = {lat: 51.5219, lng: -0.1302};
          infowindow = new google.maps.InfoWindow();
          var map = new google.maps.Map(document.getElementById('map'), {
          center: myLatlng,
          zoom: 13,
          minZoom: 2,
          mapTypeId: 'roadmap',
            
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById("pac-input");
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current maps viewport.
        map.addListener("bounds_changed", function() {
          searchBox.setBounds(map.getBounds());
        });


        var locationMarkers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener("places_changed", function() {
          var places = searchBox.getPlaces();
          var myLatlng = places;
          <?php ?>
          

          if (places.length == 0) {
            return;
          }
          
          

        
        locationMarkers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          var boundsCenter = bounds.getCenter().lat;
          var mapCenter = map.getCenter();
          console.log("bounds centre 1" + bounds.getCenter());
          console.log("bounds centre 1" + mapCenter);
          console.log(places[0].geometry);
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var placeCoords = place.geometry.location;
            locationPlaceCoords.push(place.geometry.location);
            console.log("yo yo!");
            console.log(locationPlaceCoords[0]);
            console.log("yo");
            console.log(placeCoords);
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            locationMarkers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location,
            })
            
        
        );
        console.log('yae');
            console.log(locationMarkers[0].position.lat());
            console.log(locationMarkers[0].position.lng());
            latitude = locationMarkers[0].position.lat();
            longitude = locationMarkers[0].position.lng();
            console.log('yae yae');
            console.log(latitude);
            console.log(longitude);
            document.getElementById('locLatCoords').value=latitude;
            document.getElementById('locLngCoords').value=longitude;
            
            
            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
        
        
        console.log(locationPlaceCoords);

            markers(map);
            
         var coords = <?php echo json_encode($myArray); ?>;

        function markers(map)
        {
            //have a look at this - need to do this way?
            $.getJSON('coords.json', function(data)
                
				{
                    for(i in coords)
                    {
                        var image = coords[i].imagepath;
                        var popupImage = '<br><img src="'+image+'" style="width:500px;">';
                        var points = new google.maps.LatLng(coords[i].latitude, coords[i].longitude);
                        var marker = new google.maps.Marker({map: map, position: points, clickable: true});
                        
                        
                        google.maps.event.addListener(marker, 'click', (function(marker, popupImage, infowindow){
                            return function() {
                                infowindow.setContent(popupImage);
                                infowindow.open(map, marker);
                                console.log(infowindow);
                            }
                        })(marker, popupImage, infowindow));
                        
                        
                        
                    }                    
                });
                var mydata = locationPlaceCoords;   
                          
        }

        
    }
    
    </script>
    
    <div class="yearEntry">
        <table>
        <tr>
        <form method='post'>       
        <input type='hidden' id='locLatCoords' name='locLatCoords' value="">
        <input type='hidden' id='locLngCoords' name='locLngCoords' value="">
        <td class='search-text'>
        Search Radius: <input type='text' placeholder = 'Enter radius around selected location to search' id='searchRadius' name='searchRadius'>
        </td> 
        <td class='search-text'>
        Start Year: <input type='text' placeholder = 'Enter start year value' id='yearSearchStart' name='yearSearchStart'>
        </td>
        <td class='search-text'>
        End Year: <input type='text' placeholder = 'Enter end year value' id='yearSearchEnd' name='yearSearchEnd'>
        </td>
        
        </tr>
        <tr>
        <!-- Enter tag values separated by commas: -->
        <br>
        </tr>
        <tr>
            <td class='search-text'>
        Enter tag values separated by commas: <textarea rows="4" cols="50" placeholder = 'Enter comma separated tags' id="tagSearch" name="tagSearch"></textarea>
        </td>
        </tr>
        <br>
        
        <?php
            //create the metadata drop down lists
            
            $makeResult=mysqli_query($dbc,$makeQuery);
            $modelResult=mysqli_query($dbc,$modelQuery);
            $shutterspeedResult=mysqli_query($dbc,$shutterspeedQuery);
            $apertureResult=mysqli_query($dbc,$apertureQuery);
            $isoResult=mysqli_query($dbc,$isoQuery);
            $resolutionResult=mysqli_query($dbc,$resolutionQuery);
            
            //Populate the camera make drop down list
            echo "<div>";
            echo "
                <tr>
                <td class='search-text'>Camera Make:</td>
                <td>
                <select class='custom-select' id='metadata' name='cameraMake'>
                    <option value=''>All Makes</option>";

                    while($row = mysqli_fetch_array($makeResult))
                    {
                        echo "<option value='$row[0]'>$row[0]</option>";
                    }
            echo "    
                </select>
                </td>
                </tr>
            ";


            //Populate the camera model drop down list
            echo "
                <tr>
                <td class='search-text'>Camera Model:</td>
                <td>
                <select class='custom-select' id='metadata' name='cameraModel'>
                    <option value=''>All Models</option>";

                    while($row = mysqli_fetch_array($modelResult))
                    {
                        echo "<option value='$row[0]'>$row[0]</option>";
                    }
            echo "    
                </select>
                </td>
                </tr>
            ";

            //Populate the photo shutter speed drop down list
            echo "
                <tr>
                <td class='search-text'>Shutter Speed:</td>
                <td>
                <select class='custom-select' id='metadata' name='shutterSpeed'>
                    <option value=''>Any Speed Setting</option>";

                    while($row = mysqli_fetch_array($shutterspeedResult))
                    {
                        echo "<option value='$row[0]'>$row[0]</option>";
                    }
            echo "    
                </select>
                </td>
                </tr>
            ";

            //Populate the photo aperture drop down list
            echo "
                <tr>
                <td class='search-text'>Aperture:</td>
                <td>
                <select class='custom-select' id='metadata' name='aperture'>
                    <option value=''>Any F Stop</option>";

                    while($row = mysqli_fetch_array($apertureResult))
                    {
                        echo "<option value='$row[0]'>$row[0]</option>";
                    }
            echo "    
                </select>
                </td>
                </tr>
            ";

            //Populate the photo iso drop down list
            echo "
                <tr>
                <td class='search-text'>ISO:</td>
                <td>
                <select class='custom-select' id='metadata' name='iso'>
                    <option value=''>Any ISO Setting</option>";

                    while($row = mysqli_fetch_array($isoResult))
                    {
                        echo "<option value='$row[0]'>$row[0]</option>";
                    }
            echo "    
                </select>
                </td>
                </tr>
            ";

            //Populate the photo resolution drop down list
            echo "
                <tr>
                <td class='search-text'>Resolution:</td>
                <td>
                <select class='custom-select' id='metadata' name='resolution'>
                    <option value=''>Any Resolution</option>";

                    while($row = mysqli_fetch_array($resolutionResult))
                    {
                        echo "<option value='$row[0]'>$row[0]</option>";
                    }
            echo "    
                </select>
                </td>
                </tr>
            ";

            echo "<tr>";
            echo "<td></td>";
            echo "</tr>";
        echo "</div>";

           
        ?>
        
        
        <tr>
        <td><button type='submit' class='button button-block' name='mapSearch' />Search for Images</button></td>

        </tr>
        </table>
    </div>
    

    
    
    </div>   
 
    

    <?php

if(isset($_POST['mapSearch']))
{    
    
   
    
    echo "<br>";

    echo "<h2>Image Gallery</h2>";
    
    echo 
    '
    <div class = "imageGallery">
    </div>
    ';

    
}

?>
    
    <script>

    //there's a 403 error in here somewhere...try to sort.  
    //maybe the qurey needs to restrict where there is no thumbnail

    var coords = <?php echo json_encode($myArray); ?>;
    var fullGallery = '';
    for (i in coords)
    {
        fullGallery += '<a href = "' + coords[i].imagepath + '" data-lightbox = "gallery"><img src = "'+ coords[i].thumbnailpath + '">';
    }
    $('.imageGallery').append(fullGallery);


</script>

    <script src="https://maps.googleapis.com/maps/api/js?v=3.32&key=AIzaSyD-gybpP1HdyxjzaMM5X2UcM2B1iLO4GMg&libraries=places,geometry&callback=initAutocomplete"
         async defer></script>
  </body>
</html>