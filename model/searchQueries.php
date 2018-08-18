<?php

//Search parameters
$yearStart = $_POST['yearSearchStart'];

$yearEnd = $_POST['yearSearchEnd'];

$locSearchLat = $_POST['locLatCoords'];;

$locSearchLng = $_POST['locLngCoords'];

$searchRadius = $_POST['searchRadius'];

$tags = $_POST['tagSearch'];

$cameraMake = $_POST['cameraMake'];

$cameraModel = $_POST['cameraModel'];

$shutterSpeed = $_POST['shutterSpeed'];

$aperture = $_POST['aperture'];

$iso = $_POST['iso'];

$resolution = $_POST['resolution'];

//$finalList = $_SESSION['finalList'];
//change this into a function?
$tagArray = [];
    $eachTag = explode(',', $tags);
    foreach($eachTag as $searchTag)
        {
            array_push($tagArray, $searchTag);
        }
    $tagList = json_encode($tagArray);
    $finalList = trim($tagList, '[]');
    $_SESSION['finalList'] = $finalList;


//baseline search query
$baselineSearchQuery = "SELECT * 
FROM project.images
WHERE imageid IS NOT NULL";

//filtered searches
//Location------------------------->

//Location and radius set
$locationAndRadiusQuery = 
"SELECT
                imageid, imagepath, longitude, latitude, year, thumbnailpath, make, model, shutterspeed, aperture, iso, resolution, (
                  3959 * acos (
                    cos ( radians($locSearchLat) )
                    * cos( radians( latitude ) )
                    * cos( radians( longitude ) - radians($locSearchLng) )
                    + sin ( radians($locSearchLat) )
                    * sin( radians( latitude ) )
                  )
                ) AS distance
              FROM project.images
              HAVING distance < $searchRadius";

//Location and no radius set
$locationNoRadiusQuery = 
"SELECT
                imageid, imagepath, longitude, latitude, year, thumbnailpath, make, model, shutterspeed, aperture, iso, resolution, (
                  3959 * acos (
                    cos ( radians($locSearchLat) )
                    * cos( radians( latitude ) )
                    * cos( radians( longitude ) - radians($locSearchLng) )
                    + sin ( radians($locSearchLat) )
                    * sin( radians( latitude ) )
                  )
                ) AS distance
              FROM project.images
              HAVING distance <= 1";

//Year------------------------->
//Year start set but year end blank
$yearStartQuery = " AND year >= $yearStart";

//Year start blank but year end set
$yearEndQuery = " AND year <= $yearEnd";

//Year start and year end set
$betweenYearStartAndEndQuery = " AND year >= $yearStart 
AND year <= $yearEnd";

//Tag------------------------->
//Tag value
$tagQuery = 
" AND imageid IN
            (
                select distinct imageid from project.tags
                where tag IN ($finalList)
            )";

//Metadata Searches------------------------>
//Camera make
$cameraMakeQuery = " AND make = '$cameraMake'";

//Camera model
$cameraModelQuery = " AND model = '$cameraModel'";

//Shutter Speed
$shutterSpeedQuery = " AND shutterspeed = '$shutterSpeed'";

//Aperture
$apertureQuery = " AND aperture = '$aperture'";

//ISO
$isoQuery = " AND iso = '$iso'";

//Resolution
$resolutionQuery = " AND resolution = '$resolution'";

?>