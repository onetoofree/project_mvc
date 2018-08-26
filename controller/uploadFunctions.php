<?php
// this file contains all php upload related functions to include in main script
function createThumbnail($fileName)
{
  //create thumbnail from uploaded image - thumbnail is for website/gallery display
  $im = imagecreatefromjpeg('../uploads/'.$fileName);
  $ox = imagesx($im);
  $oy = imagesy($im);

  $nx = 200;
  $ny = floor($oy * (200/$ox));

  $nm = imagecreatetruecolor($nx, $ny);

  imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

  imagejpeg($nm, '../uploads/thumbnails/'.$fileName);
}

function getTheSelectedImage($data)
{
    session_unset($_SESSION['long']);
    validateSelectedFile(); 

    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileExtension = strtolower(end($fileExt));

    $fileTypesAllowed = array('jpg', 'jpeg', 'png');    

    $fileDestination = '../uploads/'.$fileName;
    $thumbDestination = '../uploads/thumbnails/'.$fileName;
    
    //move_uploaded_file($fileTmpName, $fileDestination);

    if (move_uploaded_file($fileTmpName, $fileDestination))
    {
      createThumbnail($fileName);
    }    
    
    $_SESSION['filename'] = $fileName;
    $_SESSION['fileDestination'] = $fileDestination;
    $_SESSION['fileTempName'] = $fileTmpName;
    $_SESSION['thumbDestination'] = $thumbDestination;

}

function validateSelectedFile()
{ 
  header('Content-Type: text/html; charset=utf-8');

try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['file']['error']) ||
        is_array($_FILES['file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['file']['error'] value.
    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            die('Please select a file before clicking the "Get Image" button');
            echo "<script type='text/javascript'>alert('select a file');</script>";
            // throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            die('The file you have selected is too large.  The maximum size is 1MB.');
            // throw new RuntimeException('The file you have selected is too large.  The maximum size is 1MB.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($_FILES['file']['size'] > 1000000) {
        die('The file you have selected is too large.  The maximum size is 1MB.');
        //throw new RuntimeException('Exceeded filesize limit.');        
    }
    elseif($_FILES['file']['size'] < 40000)
    {
      die('The file you have selected is too small.  The minimum size is 40KB.');
    }

    // DO NOT TRUST $_FILES['file']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['file']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        die('The file you have selected is of an invalid format.  Please select files of type jpg/jpeg, png or gif only.');
        //throw new RuntimeException('Invalid file format.');
        
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['file']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    // $tmpfname = tempnam('../uploads','ups');
    // echo "new filename is";
    // echo "<br>";
    // echo $tmpfname;
    // echo "<br>";
    // if (!move_uploaded_file(
    //     $_FILES['file']['tmp_name'],
    //     sprintf('../uploads/%s.%s',
    //         sha1_file($_FILES['file']['tmp_name']),
    //         $ext
    //     )
    // )) {
    //     throw new RuntimeException('Failed to move uploaded file.');
    // }

    // echo 'File is uploaded successfully.';

} catch (RuntimeException $e) {

    echo $e->getMessage();

}
}

function yearFieldValidation()
{  
    $year = $_POST['year']; 
    $minimumYear = '1900';
    $currentYear = date('Y');

    // Check if year field is blank
    if (!filter_var($year, FILTER_VALIDATE_INT)) {
      exit('year must be numeric value between '.$minimumYear.' and '.$currentYear);            
    }
    elseif (!strlen($year) > 0) {
        exit('the year is empty');            
    }
    // Check if year field is less than the minimum allowed
    elseif ($year < 1900) {
      exit('year must be greater than'.$minimumYear);            
    }
        // Check if year field is greater than the current year
    elseif ($year > $currentYear) {
      exit('year must be less than '.$currentYear);            
    }
}

function locationValidation()
{
  
  $selectedLocation = $_SESSION['long'];
  // Check if location is set
  
  if (strlen($selectedLocation) == 0) {
    exit('choose a location');            
}
}

function displaySelectedImage()
{ 
  echo "<div class='uploadMap'>";
  echo "<img src={$_SESSION['thumbDestination']} class='uploadMap'>";
  echo "</div>";
}

function tagImage()
{
  echo "<div class='uploadMap'>";  
  echo "<h1>Selected Image</h1>";
  echo "<img src={$_SESSION['thumbDestination']} class='selectedImage'>";
  echo "<form action='onePageUpload.php' method='post'>";    
  echo "<input type='text' placeholder = 'Enter year value here *' id='year' name='year'><br>";
  echo '<input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map" class="uploadMap"></div>
    <div id="result" class="selectedImage"></div>';
  echo '<script src="../pages/js/uploadMap.js"></script>';
  echo "<button type='submit' class='button button-block' name='uploadImage' />Upload Image and Details</button>";
  echo "</div>";
}

function displayUploadImage()
{
  echo "<div class='uploadMap'>";  
  echo "<h1>Selected Image</h1>";
  
  //display the selected image
  echo "<img src={$_SESSION['thumbDestination']} class='selectedImage'>";
  echo "<form action='uploadImages.php' method='post'>";
  
  //display the year field  
  echo "Year: <input type='text' placeholder = 'Enter year value here *'' id='year' name='year'><br>";
  
  //display the map and its search box
  echo '<input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map" class="uploadMap"></div>
    <div id="result" class="selectedImage"></div>';
  echo '<script src="../view/js/uploadMap.js"></script>';
  echo "<button type='submit' class='button button-block' name='uploadImage' />Upload Image and Details</button>";
  
  echo "</div>";
}

function uploadTheSelectedImage()
{
  require '../model/db_connect.php';
  require '../model/uploadQueries.php';

  yearFieldValidation();
  locationValidation();
  // die('Please select a file before clicking the "Get Image" button');

  // move_uploaded_file($fTmpName, $fDestination);
  
  //execute the database instertion
  $dbc->query($insertSelectedImageToDatabaseQuery);

  //navigate to the tagging page
//   header("location: uploadImages.php");
  header("location: tagImages.php");
}

function getVisionTags($selectedFile)
{
  $resultingTags = exec("python /Library/WebServer/Documents/historicPhotos/controller/imageRecognition/imageRecognition.py $selectedFile");
  return $resultingTags;
}

function readExifFromUploadedImages($selectedFile)
{ 
  //assign image metadata to a variable 
  $exif_data = exif_read_data($selectedFile);

  //get the shutter speed from exif
  $shutterSpeed = $exif_data['ExposureTime'];

  //get the numerical values from shutter speed
  $shutterSpeedMultiplier = explode('/', $shutterSpeed);
  
  //the shutter speed in the exif for some images is multiplied by 10.
  //the below checks if that's the case in order to return the correct shutter speed value
  if ($shutterSpeedMultiplier[0] == 10)
  {
    $actualShutterSpeed1 = $shutterSpeedMultiplier[0]/$shutterSpeedMultiplier[0];
    $actualShutterSpeed2 = $shutterSpeedMultiplier[1]/$shutterSpeedMultiplier[0];
    $actualShutterSpeed = $actualShutterSpeed1.'/'.$actualShutterSpeed2;
  }
  else
  {
    $actualShutterSpeed = $exif_data['ExposureTime'];
  }

  //get the resolution from exif
  $resolution = $exif_data['XResolution'];

  //get the numerical value from resolution
  $resMultiplier = explode('/', $resolution);

  if($resMultiplier[0] > 0)
  {
    $actualResolution = $resMultiplier[0].'dpi';
  }
  else
  {
    $actualResolution = $resolution;
  }
    
  //make the exif values available for db insertion
  $_SESSION['Make'] = $exif_data['Make'];
  $_SESSION['Model'] = $exif_data['Model'];
  $_SESSION['ExposureTime'] = $actualShutterSpeed;
  $_SESSION['ApertureFNumber'] = $exif_data['COMPUTED']['ApertureFNumber'];
  $_SESSION['ISOSpeedRatings'] = $exif_data['ISOSpeedRatings'];
  $_SESSION['XResolution'] = $actualResolution;
}

function displayTags($fDestination)
{
  //get suggested tags from Google Vision API
  $googleVisionApiOutput = getVisionTags($fDestination);

  //set tag data into useful format
  $tags = preg_replace("/[^a-zA-Z0-9,]+/", "", $googleVisionApiOutput);
    
    //empty array to take selected tags
    $selection = [];
    
    //capture the selected/posted tag options 
    $checked = $_POST['options'];
    echo "<div class='tagSelector'>";
    echo "<h2>Select tags from the below suggestions</h2>";
    echo "<form id='tagSelection' method='post'>"; 
    echo "<table cellspacing='3'>";
    echo "<tbody>"; 
    echo "<tr id='heading'>";              
    echo "</tr>";

    //add all suggested tags to the page as checkbox options
    $eachTag = explode(',', $tags);
    foreach($eachTag as $suggestedTag)
    {
        echo "<tr>";          
        echo "<td>$suggestedTag</td>";            
        echo "<td>";            
        echo "<input type='checkbox' id='$suggestedTag' name='options[]' value=$suggestedTag/>";           
        echo "</td>";            
        echo "</tr>";   
    }

    //custom tag free text field
    echo "<tr>";
    echo "<td>";
    echo "<input type='text' placeholder = '...or enter a custom tag here' name='options[]' id='manuallyEnteredTags'/>";
    echo "</td>";
    echo "</tr>";
    
    //submit tags button
    echo "<tr>";
    echo "<td>";
    echo "<button type='submit' class='button button-block' name='addTags' />Click Here to Add Tags to Images</button>";
    echo "</td>";    
    echo "</tr>";

    echo "</tbody>";
    echo "</table>"; 
    echo "</form>"; 
    echo "</div>";

    //add selected tags to the array of selected tags
    for($i=0; $i < count($checked); $i++)
    {
        if(strlen($checked[$i]) > 0)
        {
            array_push($selection, $checked[$i]);
        }        
    }
    
    //might use some of this stuff to add selected tag details to the page
    print_r($selection);
    echo "<br>";
    
    //format the selected tags in a way that can used to be added to the database
    $finalTags = preg_replace("/[^a-zA-Z0-9]+/", "", $selection);

    print_r($finalTags);
    echo "<br>";

    foreach($finalTags as $selectedTag)
    {
        print_r($selectedTag);
        echo "<br>";
        
    }
    echo "there are ".count($finalTags)." tags selected";
    echo "<br>";
    echo "the final tags are: ".$selection;
    echo "<br>";
    $_SESSION['listOfTags'] = $finalTags;
    $listOfTags = $_SESSION['listOfTags'];
    print_r($listOfTags);
    echo "<br>";
    foreach($finalTags as $sessionedTags)
    {
        $_SESSION[$sessionedTags] = $sessionedTags;
    }
}

function addTagsToImages()
{
  require '../model/db_connect.php';
  require '../model/uploadQueries.php';
  
  //get the list of selected tags
  $tagsToBeAdded = $_SESSION['listOfTags'];

  foreach($tagsToBeAdded as $tagValue)
  {
    //imageId for image to link tag to
    $getImageId = $dbc->query($getImageIdQuery);

    $returnedImageId = $getImageId->fetch_assoc();
    $imageId = $returnedImageId['imageid'];
    
//insert image tags into the database
//try to get into model
    $insertTagSql = "INSERT INTO tags 
    (tag, imageId) 
    VALUES 
    ('$tagValue', '$imageId')";
    
    $dbc->query($insertTagSql);  
  }  
}
?>