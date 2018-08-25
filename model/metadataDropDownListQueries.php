<?php

//query to get values for camera make drop down list
$makeQuery = "SELECT DISTINCT make
FROM project.images
WHERE make <>''
ORDER BY make";

//query to get values for camera model drop down list
$modelQuery = "SELECT DISTINCT model
FROM project.images
WHERE model <>''
ORDER BY model";

//query to get values for shutter speed drop down list
$shutterspeedQuery = "SELECT DISTINCT shutterspeed
FROM project.images
WHERE shutterspeed <>''
ORDER BY shutterspeed";

//query to get values for aperture drop down list
$apertureQuery = "SELECT DISTINCT aperture
FROM project.images
WHERE aperture <>''
ORDER BY aperture";

//query to get values for iso drop down list
$isoQuery = "SELECT DISTINCT iso
FROM project.images
WHERE iso <>''
ORDER BY iso";

//query to get values for resolution drop down list
$resolutionQuery = "SELECT DISTINCT resolution
FROM project.images
WHERE resolution like '%dpi%'
ORDER BY resolution";

?>