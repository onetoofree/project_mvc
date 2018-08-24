<?php 
require '../dbconnection/db_connect.php';
session_start();

ob_start();


?>


<!DOCTYPE html>
<html>
<head>
	<title>Image Upload</title>
	<script src="http://code.jquery.com/jquery-latest.min.js" charset="utf-8"></script>
</head>


<body>
<!-- <h1>Submission Form</h1> -->



<?php

$longitude = $_POST['postlng'];
$latitude = $_POST['postlat'];
$yearValue = $_POST['year'];

$_SESSION['long'] = $longitude;
$_SESSION['lat'] = $latitude;

echo "<br>";
echo "the longitutde isss: $longitude";
echo "<br>";
echo "the latitude isss: $latitude";
echo "<br>";
echo "the year is: $yearValue"

?>

</table>

</div>


</body>
</html>
