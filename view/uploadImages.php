<?php 
require '../controller/uploadFunctions.php';
session_start();

ob_start();
putenv('GOOGLE_APPLICATION_CREDENTIALS=/Library/WebServer/Documents/project/apiKey.json');
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Image Upload</title>
    <script src="http://code.jquery.com/jquery-latest.min.js" charset="utf-8"></script>
    <?php include 'css/css.html'; ?>
</head>

<body>
<div class="form">
  <div id="upload"> 
    <h1>Upload Images Here</h1>
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="file"><br>
      <button class="button button-block" type="submit" name="submit" />Get Image</button>
    </form>
  </div>
</div>

<?php
if(isset($_POST['submit']))
{  
  getTheSelectedImage($_FILES);   
}

$fDestination = $_SESSION['fileDestination'];
if(isset($_FILES['file']))
{
  displayUploadImage();  
  readExifFromUploadedImages($fDestination);    
}

if(isset($_POST['uploadImage']))
{
  uploadTheSelectedImage($_FILES);     
}
?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.32&key=AIzaSyD-gybpP1HdyxjzaMM5X2UcM2B1iLO4GMg&libraries=places&callback=initAutocomplete"
         async defer></script>
</body>
</html>