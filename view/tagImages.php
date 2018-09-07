<?php 
require '../model/db_connect.php';
require '../controller/uploadFunctions.php';
session_start();
    if(!$_SESSION['logged_in']==true)
    {
      header("location: userLogin.php");
    }

ob_start();
putenv('GOOGLE_APPLICATION_CREDENTIALS=/Library/WebServer/Documents/project/apiKey.json');
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Tag Images</title>
    <script src="http://code.jquery.com/jquery-latest.min.js" charset="utf-8"></script>
    <?php include 'css/css.html'; ?>
</head>

<body>
<div class="form">
<h1 name="tagQuestion">Would you like to tag your uploaded image?</h1>  

<?php

$fDestination = $_SESSION['fileDestination'];
displaySelectedImage();
displayTags($fDestination);

if(isset($_POST['addTags']))
{
    addTagsToImages();
    $tagsToBeAdded = $_SESSION['listOfTags'];     
}

if(isset($_POST['profile']))
{   
    header("location: profile.php");    
}
?>

</div>
</body>
</html>