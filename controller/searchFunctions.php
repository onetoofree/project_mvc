<?php
// this file contains all php search related functions to include in main script
function performSearch()
{
    require '../model/db_connect.php';
    require '../model/searchQueries.php';

    if(isset($_POST['imageSearch']))
    {
    
    $query = $baselineSearchQuery;

    if($stmt = $dbc->prepare($query))
    {
        //Adding dynamic query for the location
        if(strlen($_POST['locLatCoords']) > 0 && strlen($_POST['searchRadius']) > 0)
        {
            $query = $locationAndRadiusQuery;

             echo $query;
             echo "<br>";
        }
        elseif(strlen($_POST['locLatCoords']) > 0 && strlen($_POST['searchRadius']) == 0)
        {
            // echo "location is set";
            // echo "<br>";
            $query = $locationNoRadiusQuery;

             echo $query;
             echo "<br>";
        }

        //Adding dynamic query for the year start
        if(strlen($_POST['yearSearchStart']) > 0 && strlen($_POST['yearSearchEnd']) == 0)
        {
            echo "yearSearchStart is set and yearSearchEnd isn't";
            echo "<br>";
            $query .= $yearStartQuery;

            echo $query;
            echo "<br>";
        }
        //Adding dynamic query for the year end
        elseif(strlen($_POST['yearSearchStart']) == 0 && strlen($_POST['yearSearchEnd']) > 0)
        {
            echo "yearSearchStart is not set and yearSearchEnd is";
            echo "<br>";
            $query .= $yearEndQuery;

            echo $query;
            echo "<br>";
        }
        //Adding dynamic query for the between years
        elseif(strlen($_POST['yearSearchStart']) > 0 && strlen($_POST['yearSearchEnd']) > 0)
        {
            echo "yearSearchStart is set and yearSearchEnd is set";
            echo "<br>";
            $query .= $betweenYearStartAndEndQuery;
            
            echo $query;
            echo "<br>";
        }
        
        //Adding dynamic query for the image tag
        if(strlen($_POST['tagSearch']) > 0)
        {
            // echo "tagSearch is set";
            // echo "<br>";
            $query .= $tagQuery;

             echo $query;
            echo "<br>";
        }

        //Adding dynamic query for the camera make
        if(strlen($_POST['cameraMake']) > 0)
        {
            // echo "cameraMake is set";
            // echo "<br>";
            $query .= $cameraMakeQuery;

            echo $query;
             echo "<br>";
        }

        //Adding dynamic query for the camera model
        if(strlen($_POST['cameraModel']) > 0)
        {
            // echo "cameraModel is set";
            // echo "<br>";
            $query .= $cameraModelQuery;

             echo $query;
             echo "<br>";
        }

        //Adding dynamic query for the camera shutter speed
        if(strlen($_POST['shutterSpeed']) > 0)
        {
            // echo "shutterSpeed is set";
            // echo "<br>";
            $query .= $shutterSpeedQuery;

             echo $query;
             echo "<br>";
        }

        //Adding dynamic query for the camera aperture
        if(strlen($_POST['aperture']) > 0)
        {
            // echo "aperture is set";
            // echo "<br>";
            $query .= $apertureQuery;

             echo $query;
             echo "<br>";
        }

        //Adding dynamic query for the camera ISO setting
        if(strlen($_POST['iso']) > 0)
        {
            // echo "iso is set";
            // echo "<br>";
            $query .= $isoQuery;

             echo $query;
             echo "<br>";
        }

        //Adding dynamic query for the image resolution
        if(strlen($_POST['resolution']) > 0)
        {
            // echo "resolution is set";
            // echo "<br>";
            $query .= $resolutionQuery;

             echo $query;
             echo "<br>";
        }

        //add the order by
        $query = $query .=$orderByQueryEnd;
        
        $stmt = $dbc->prepare($query);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        $myArray = array();
        while ($myrow = $result->fetch_assoc())
        {
            $myArray[] = $myrow;
        }
    }
   
}
return $myArray;
}

function displayFilters()
{
    //data for set filters message
$latitudeSet = $_POST['locLatCoords'];
$longitudeSet = $_POST['locLngCoords'];
$startYearSet = $_POST['yearSearchStart'];
$endYearSet = $_POST['yearSearchEnd'];
$radiusSet = $_POST['searchRadius'];
$tagsSet = $_POST['tagSearch'];
$cameraMakeSet = $_POST['cameraMake'];
$cameraModelSet = $_POST['cameraModel'];
$shutterSpeedSet = $_POST['shutterSpeed'];
$apertureSet = $_POST['aperture'];
$isoSet = $_POST['iso'];
$resolutionSet = $_POST['resolution'];
    if(strlen($latitudeSet) > 0 || strlen($longitudeSet) > 0 || strlen($startYearSet) > 0 || strlen($endYearSet) > 0 || strlen($radiusSet) > 0 || strlen($tagsSet) > 0 || strlen($cameraMakeSet) > 0 || strlen($cameraModelSet) > 0 || strlen($shutterSpeedSet) > 0 || strlen($apertureSet) > 0 || strlen($isoSet) > 0 || strlen($resolutionSet) > 0)
        {
            echo "<h2 name='filtersSet'>The following filters are set: </h2>";
            if(strlen($latitudeSet) > 0 && (strlen($longitudeSet) > 0))
            {
                echo "<h3>location: ".$latitudeSet.",".$longitudeSet."</h3>";            
            }
            if(strlen($startYearSet) > 0)
            {
                echo "<h3>greater than or equal to year: ".$startYearSet."</h3>";            
            }
            if(strlen($endYearSet) > 0)
            {
                echo "<h3>less than or equal to year: ".$endYearSet."</h3>";            
            }
            if(strlen($radiusSet) > 0)
            {
                echo "<h3>mile radius around selected location: ".$radiusSet."</h3>";            
            }
            if(strlen($tagsSet) > 0)
            {
                echo "<h3>tags: ".$tagsSet."</h3>";            
            }
            if(strlen($cameraMakeSet) > 0)
            {
                echo "<h3>camera make: ".$cameraMakeSet."</h3>";            
            }
            if(strlen($cameraModelSet) > 0)
            {
                echo "<h3>camera model: ".$cameraModelSet."</h3>";            
            }
            if(strlen($shutterSpeedSet) > 0)
            {
                echo "<h3>shutter speed: ".$shutterSpeedSet."</h3>";            
            }
            if(strlen($apertureSet) > 0)
            {
                echo "<h3>aperture: ".$apertureSet."</h3>";            
            }
            if(strlen($isoSet) > 0)
            {
                echo "<h3>ISO: ".$isoSet."</h3>";            
            }
            if(strlen($resolutionSet) > 0)
            {
                echo "<h3>resolution: ".$resolutionSet."</h3>";            
            }
        }
    else
        {
            echo "<h2>No filters are set</h2>";
        }
}

?>