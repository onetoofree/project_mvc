<?php 
session_start();
ob_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Image Upload</title>
</head>

<body>

<?php

$longitude = $_POST['postlng'];
$latitude = $_POST['postlat'];
$yearValue = $_POST['year'];

$_SESSION['long'] = $longitude;
$_SESSION['lat'] = $latitude;
echo "<div class='search-text'>";
echo "The following location was selected:";
echo "<br>";
echo "Latitude: $latitude";
echo "<br>";
echo "Longitude: $longitude";
echo "<br>";
echo "</div>";

?>

</body>
</html>
