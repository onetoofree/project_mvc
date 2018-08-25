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
// header('Content-Type: text/html; charset=utf-8');

// try {
    
//     // Undefined | Multiple Files | $_FILES Corruption Attack
//     // If this request falls under any of them, treat it invalid.
//     if (
//         !isset($_FILES['file']['error']) ||
//         is_array($_FILES['file']['error'])
//     ) {
//         throw new RuntimeException('Invalid parameters.');
//     }

//     // Check $_FILES['file']['error'] value.
//     switch ($_FILES['file']['error']) {
//         case UPLOAD_ERR_OK:
//             break;
//         case UPLOAD_ERR_NO_FILE:
//             throw new RuntimeException('No file sent.');
//         case UPLOAD_ERR_INI_SIZE:
//         case UPLOAD_ERR_FORM_SIZE:
//             throw new RuntimeException('Exceeded filesize limit.');
//         default:
//             throw new RuntimeException('Unknown errors.');
//     }

//     // You should also check filesize here. 
//     if ($_FILES['file']['size'] > 1000000) {
//         throw new RuntimeException('Exceeded filesize limit.');
//     }

//     // DO NOT TRUST $_FILES['file']['mime'] VALUE !!
//     // Check MIME Type by yourself.
//     $finfo = new finfo(FILEINFO_MIME_TYPE);
//     if (false === $ext = array_search(
//         $finfo->file($_FILES['file']['tmp_name']),
//         array(
//             'jpg' => 'image/jpeg',
//             'png' => 'image/png',
//             'gif' => 'image/gif',
//         ),
//         true
//     )) {
//         throw new RuntimeException('Invalid file format.');
//     }

//     // You should name it uniquely.
//     // DO NOT USE $_FILES['file']['name'] WITHOUT ANY VALIDATION !!
//     // On this example, obtain safe unique name from its binary data.
//     // $tmpfname = tempnam('../uploads','ups');
//     // echo "new filename is";
//     // echo "<br>";
//     // echo $tmpfname;
//     // echo "<br>";
//     // if (!move_uploaded_file(
//     //     $_FILES['file']['tmp_name'],
//     //     sprintf('../uploads/%s.%s',
//     //         sha1_file($_FILES['file']['tmp_name']),
//     //         $ext
//     //     )
//     // )) {
//     //     throw new RuntimeException('Failed to move uploaded file.');
//     // }

//     echo 'File is uploaded successfully.';

// } catch (RuntimeException $e) {

//     echo $e->getMessage();

// }

?>

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