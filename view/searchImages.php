<?php


require '../pages/include/searchFunctions.php';
require '../dbconnection/db_connect.php';
require 'include/metadataDropDownListQueries.php';
//session_start();

ob_start();

$myArray = performSearch();

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Next Map</title>
    <link rel = "stylesheet" type = "text/css" href = "lightbox.min.css">
    <script type = "text/javascript" src="lightbox-plus-jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" charset="utf-8"></script>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        /* height: 100%; */
        width: 1000px;
        height: 600px;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
      }

      #infowindow-content .title {
        font-weight: bold;
      }

      #infowindow-content {
        display: none;
      }

      #map #infowindow-content {
        display: inline;
      }

      .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
      }

      #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
      }

      .pac-controls {
        display: inline-block;
        padding: 5px 11px;
      }

      .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
      }
      #target {
        width: 345px;
      }

      body
        {
            font-family: sans-serif;
        }
        h1 
        {
            text-align: center;
            color: forestgreen;
            margin: 30px 0 50px
        }
        .imageGallery
        {
            margin: 10px 50px;
        }
        .imageGallery img
        {
            width: 230px;
            padding: 5px;
            /* filter: grayscale(100%); */
            transition: 0.5s;
        }
        .imageGallery img:hover
        {
            /* filter: grayscale(0); */
            transform: scale(1.1);
        }
    </style>
  </head>
  <body>
    
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
        //   var opt = { minZoom: 6, maxZoom: 9 };
          infowindow = new google.maps.InfoWindow();
          var map = new google.maps.Map(document.getElementById('map'), {
          //center: {lat: 51.5074, lng: 0.1278}
          center: myLatlng,
          zoom: 13,
          //maxZoom: 15,
          minZoom: 2,
        //   zoom: minZoomLevel,
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
          //console.log(places);
          <?php ?>
          

          if (places.length == 0) {
            return;
          }
          
          

        
        locationMarkers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          var boundsCenter = bounds.getCenter().lat;
          var mapCenter = map.getCenter();
        //   console.log(boundsCenter);
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
            
            //locationPlaceCoords.push("" + place.geometry.location);
            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
        
        console.log("oyyy");
        //locationPlaceCoords.push("locationCoordssss" + place.geometry.location);
        console.log(locationPlaceCoords);

            markers(map);
            
         var coords = <?php echo json_encode($myArray); ?>;
        

        function markers(map)
        {
            $.getJSON('coords.json', function(data)
                
				{
                    for(i in coords)
                    {
                        var image = coords[i].imagepath;
                        var popupImage = '<br><img src="'+image+'" style="width:500px;">';
                        var points = new google.maps.LatLng(coords[i].latitude, coords[i].longitude);
                        var marker = new google.maps.Marker({map: map, position: points, clickable: true});
                        // var info = new google.maps.InfoWindow({content: popupImage});
                        //var info = null;
                        //console.log(popupImage);

                        //console.log("image path is: " + image);
                        
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
                console.log("mydataaaa");
                console.log(mydata);      
                //geocoder = new google.maps.Geocoder();             
        }

        
    }
    
    </script>
    
    <div class="yearEntry">
        <table>

        <!-- <h4>Search by year</h4> -->
        <tr>
        <!-- <td><form action='plotMarkers.php' method='post'></td> -->
        <td><form method='post'></td>        
       
        Start Year: <input type='text' id='yearSearchStart' name='yearSearchStart' value='4000'><br>
        End Year: <input type='text' id='yearSearchEnd' name='yearSearchEnd' value='5000'><br>
        <!-- <input type='hidden' name='locLatCoords' value='51.5083466'>
        <input type='hidden' name='locLngCoords' value='-0.10841579999998885'> -->
        <input type='hidden' id='locLatCoords' name='locLatCoords' value="">
        <input type='hidden' id='locLngCoords' name='locLngCoords' value="">
        Search Radius: <input type='text' id='searchRadius' name='searchRadius' value='3000'><br>
        <!-- Enter tag values separated by commas: <input type='text' id='tagSearch' name='tagSearch'><br> -->
        <tr>
        <!-- <br> -->
        Enter tag values separated by commas:
        <br>
        </tr>
        <tr>
        <textarea rows="4" cols="50" id="tagSearch" name="tagSearch"></textarea>
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
            echo "
                <tr>
                <td>Camera Make</td>
                <td>
                <select id='metadata' name='cameraMake'>
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
                <td>Camera Model</td>
                <td>
                <select id='metadata' name='cameraModel'>
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
                <td>Shutter Speed</td>
                <td>
                <select id='metadata' name='shutterSpeed'>
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
                <td>Aperture</td>
                <td>
                <select id='metadata' name='aperture'>
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
                <td>ISO</td>
                <td>
                <select id='metadata' name='iso'>
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
                <td>Resolutions</td>
                <td>
                <select id='metadata' name='resolution'>
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

           
        ?>
        
        
        <tr>
            <td><button type='submit' name='mapSearch' />Search for Images</button></td>
        </tr>
        </table>
    </div>
    

    <script>
      console.log("tags");
      //console.log();
    </script>
    <!-- <textarea rows="4" cols="50">
    </textarea> -->

    <!-- <div class="tagSearch">
        <table>
        <tr>
        <td><form action='plotMarkers.php' method='post'></td>        
        Enter tag values separated by commas: <input type='text' id='tagSearch' name='tagSearch'><br>
        </table>
    </div>   -->
    
    

    <?php

if(isset($_POST['mapSearch']))
{    
    
    // $startYear = $_POST['yearSearchStart'];
    // $endYear = $_POST['yearSearchEnd'];
    // echo "the search value is from $startYear to $endYear";
    
    echo "<br>";

    echo "<h1>Image Gallery</h1>";
    //$coords = json_encode($myArray);
    //echo $coords;
    //echo "yo";
    //echo "<br>";
    // echo "tags: ".$tags;
    // echo "<br>";
    // echo "tagList: ".$tagList;
    // //$finalList = implode(',', (array)$tagList);
    // echo "<br>";
    // echo "finalList: ".$finalList;
    // echo "<br>";
    // // print_r($finalList);
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