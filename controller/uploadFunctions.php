<?php
// this file contains all php upload related functions to include in main script
function createThumbnail($fileName)
{
  //create thumbnail from uploaded image - thumbnail is for website/gallery display
  $fileExt = explode('.', $fileName);
  $fileExtension = strtolower(end($fileExt));
  $_SESSION['fileExten'] = $fileExtension;
  if($fileExtension == 'jpg' || $fileExtension == 'jpeg')
  {
    $im = imagecreatefromjpeg('../uploads/'.$fileName);
    $ox = imagesx($im);
    $oy = imagesy($im);

    $nx = 200;
    $ny = floor($oy * (200/$ox));

    $nm = imagecreatetruecolor($nx, $ny);

    imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

    imagejpeg($nm, '../uploads/thumbnails/'.$fileName);
  }

  if($fileExtension == 'png')
  {
    $im = imagecreatefrompng('../uploads/'.$fileName);
    $ox = imagesx($im);
    $oy = imagesy($im);

    $nx = 200;
    $ny = floor($oy * (200/$ox));

    $nm = imagecreatetruecolor($nx, $ny);

    imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

    imagejpeg($nm, '../uploads/thumbnails/'.$fileName);
  }

  if($fileExtension == 'gif')
  {
    $im = imagecreatefromgif('../uploads/'.$fileName);
    $ox = imagesx($im);
    $oy = imagesy($im);

    $nx = 200;
    $ny = floor($oy * (200/$ox));

    $nm = imagecreatetruecolor($nx, $ny);

    imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

    imagejpeg($nm, '../uploads/thumbnails/'.$fileName);
  }
}

function getTheSelectedImage($data)
{
    unset($_SESSION['long']);
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
    if (
        !isset($_FILES['file']['error']) ||
        is_array($_FILES['file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }
    // Check the $_FILES['file']['error'] value.
    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo "<div name='sizeError'>The file you have selected is too large.  The maximum size is 1.5MB.</div>";
            die();
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // check filesize here too. 
    if ($_FILES['file']['size'] > 1500000) {
      echo "<p>The file you have selected is too large.  The maximum size is 1.5MB.</p>";
      die();           
    }
    elseif($_FILES['file']['size'] < 40000)
    {
      echo "<div name='sizeError'>The file you have selected is too small.  The minimum size is 40KB.</div>";
            die();
    }

    // Check file type
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
        echo "<div name='sizeError'>The file you have selected is of an invalid format.  Please select files of type jpg/jpeg, png or gif only.</div>";
        die();
    }

} catch (RuntimeException $e) {

    echo $e->getMessage();

}
}

function locationValidation()
{
  
  $selectedLocation = $_SESSION['long'];
  // Check if location is set
  
  if (strlen($selectedLocation) == 0) {
    $_SESSION['message'] = "You didn't select a location";
    header("location: error.php");
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
  echo "<input type='text' required placeholder = 'Enter year value here *' id='year' name='year'><br>";
  echo '<input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map" class="uploadMap"></div>
    <div id="result" class="selectedImage"></div>';
  echo '<script src="../pages/js/uploadMap.js"></script>';
  echo "<button type='submit' class='button button-block' name='uploadImage' />Upload Image and Details</button>";
  echo "</div>";
}

function displayOnImageSelection()
{
  //get the exifdata
  $fDestination = $_SESSION['fileDestination'];
  readExifFromUploadedImages($fDestination);

  $year = $_POST['year']; 
  $minimumYear = '1826';
  $currentYear = date('Y');  
  $yearFromExif = $_SESSION['ExifYear'];

  echo "<div class='uploadMap'>";  
  echo "<h1 name='selectedImage'>Selected Image</h1>";
  
  //display the selected image
  echo "<img src={$_SESSION['thumbDestination']} class='selectedImage'>";
  echo "<form action='uploadImages.php' method='post'>";
  
  //display the year field
  if(strlen($yearFromExif) > 0)
  {
    echo "<div class='search-text'>";
    echo "<div name='yearValueFoundMessage'>";
    echo "The year value ".$yearFromExif." was found in the image's metadata.  It hase been entered into the year field below";
    echo "<br>";
    echo "Please enter the correct value if you don't want to use the value from the metadata";
    echo "<br>";
    echo "</div>";
    echo "<br>";
    echo "Image Year: <input type='number' min='$minimumYear' max='$currentYear' required placeholder = 'Enter year value here *'' id='year' name='year' value='$yearFromExif'><br>";
    echo "Click the map below to select the location the image was taken:";
  }
  else
  {
    echo "<div class='search-text'>";  
    echo "Image Year: <input type='number' min='$minimumYear' max='$currentYear' required placeholder = 'Enter year value here *'' id='year' name='year'><br>";
    echo "Click the map below to select the location the image was taken:";
  }
  
  //if the exif contains GPS coordinates, use the values
  useExifCoordinates();
  
  //display the map and its search box
  echo '<input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map" class="uploadMap"></div>
    <div id="result" class="selectedImage"></div>';
  
  echo '<script src="../view/js/uploadMap.js"></script>';
  echo "</div>";
  
  //display exif if extracted
  displayExifOnUploadPage();
}

function useExifCoordinates()
{
  if(strlen($_SESSION['GPSLatitude'] > 0))
  {
    $exifCoordinates =  $_SESSION['GPSLatitude'].",".$_SESSION['GPSLongitude'];
    $exifLat = $_SESSION['GPSLatitude'];
    $exifLong = $_SESSION['GPSLongitude'];

    $_SESSION['lat'] = $exifLat;
    $_SESSION['long'] = $exifLong;
    
    echo "<br>";
    echo "<div name='locationFoundMessage'>";
    echo "Alternatively, use the location ".$exifCoordinates." found in the selected image's metadata";
    echo "<br>";
    echo "It has already been set so once the year value is set, you can submit for upload";
    echo "</div>";
  }

}

function displayExifOnUploadPage()
{
  $cameraMake = $_SESSION['Make'];
  $cameraModel = $_SESSION['Model'];
  $shutterSpeed = $_SESSION['ExposureTime'];
  $aperture = $_SESSION['ApertureFNumber'];
  $iso = $_SESSION['ISOSpeedRatings'];
  $resolution = $_SESSION['XResolution'];
  $imageYear = $_SESSION['ExifYear'];
  $imageLatitude = $_SESSION['GPSLatitude'];
  $imageLongitude = $_SESSION['GPSLongitude'];
  echo "<div class='search-text' name='foundExifDisplayed'>";  
  if(strlen($cameraMake) > 0 || strlen($cameraModel) > 0 || strlen($shutterSpeed) > 0 || strlen($aperture) > 0 || strlen($iso) > 0 || strlen($resolution) > 0 || strlen($imageYear) > 0 || strlen($imageLatitude) > 1 || strlen($imageLongitude) > 1)
  {
    echo "The following exif was extracted from the selected image: ";
    echo "<br>";
  }
  if(strlen($cameraMake) > 0)
  {
    echo "Camera Make: ".$cameraMake;
    echo "<br>";
  }
  if(strlen($cameraModel) > 0)
  {
    echo "Camera Model: ".$cameraModel;
    echo "<br>";
  }
  if(strlen($shutterSpeed) > 0)
  {
    echo "Shutter Speed: ".$shutterSpeed;
    echo "<br>";
  }
  if(strlen($aperture) > 0)
  {
    echo "Aperture: ".$aperture;
    echo "<br>";
  }
  if(strlen($iso) > 0)
  {
    echo "ISO Setting: ".$iso;
    echo "<br>";
  }
  if(strlen($resolution) > 0)
  {
    echo "Resolution: ".$resolution;
    echo "<br>";
  }
  if(strlen($imageYear) > 0)
  {
    echo "Image Year from Exif: ".$imageYear;
    echo "<br>";
  }
  if(strlen($imageLatitude) > 1)
  {
    echo "Image Latitude Exif: ".$imageLatitude;
    echo "<br>";
  }
  if(strlen($imageLongitude) > 1)
  {
    echo "Image Longitude Exif: ".$imageLongitude;
    echo "<br>";
  }

  echo "</div>";
  echo "<button type='submit' class='button button-block' name='uploadImage' />Upload Image and Details</button>";
  echo "</div>";
}

function uploadTheSelectedImage()
{
  require '../model/db_connect.php';
  require '../model/uploadQueries.php';

  $selectedLocation = $_SESSION['long'];
  
  if (strlen($selectedLocation) == 0) 
  {
    echo "<div name='locationError'>Please select a location before uploading the image</div>";       
  }
  else
  {
    $dbc->query($insertSelectedImageToDatabaseQuery);
    header("location: tagImages.php");
  }
}

function getVisionTags($selectedFile)
{
  $resultingTags = exec("python /Library/WebServer/Documents/historicPhotos/controller/imageRecognition/imageRecognition.py $selectedFile");
  return $resultingTags;
}

function readExifFromUploadedImages($selectedFile)
{ 
  //get the file extension - exif read from jpgs only
  $fileExten = $_SESSION['fileExten'];

  if($fileExten == 'jpg'  || $fileExten == 'jpeg')
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

    //get the year of the photo from the exif
    $date = $exif_data['DateTime'];
    $dateExploded = explode(':', $date);
    $yearOfImage = $dateExploded[0];
      
    //make the exif values available for db insertion
    $_SESSION['Make'] = $exif_data['Make'];
    $_SESSION['Model'] = $exif_data['Model'];
    $_SESSION['ExposureTime'] = $actualShutterSpeed;
    $_SESSION['ApertureFNumber'] = $exif_data['COMPUTED']['ApertureFNumber'];
    $_SESSION['ISOSpeedRatings'] = $exif_data['ISOSpeedRatings'];
    $_SESSION['XResolution'] = $actualResolution;
    $_SESSION['ExifYear'] = $yearOfImage;
    
    //get the GPS data and convert from DMS to decimal
    ConvertLatDMStoDEC();
    ConvertLngDMStoDEC();
  }
}

function ConvertLatDMStoDEC()
{
  //get the exifdata
  $fDestination = $_SESSION['fileDestination'];
  $exif_data = exif_read_data($fDestination);
  
  //reset the coordinate
  $_SESSION['GPSLatitude'] = "";
  
  //convert the coordinate from dms to decimal
  if($exif_data['GPSLatitude'][0])
  {
    $rawDeg = $exif_data['GPSLatitude'][0];
    $rawMin = $exif_data['GPSLatitude'][1];
    $rawSec = $exif_data['GPSLatitude'][2];

    $degExploded = explode('/', $rawDeg);
    $minExploded = explode('/', $rawMin);
    $secExploded = explode('/', $rawSec);

    $deg = $degExploded[0];
    $min = $minExploded[0];
    $sec = $secExploded[0]/$secExploded[1];
    
    //get the Longitude Reference
    $exifLongitudeRef = $exif_data['GPSLatitudeRef'];
    
    //Longitude Reference determines the conversion
    if($exifLongitudeRef != 'S')
    {
      $exifLatitude = $deg+((($min*60)+($sec))/3600);
    }
    else
    {
      $exifLatitude = $deg+((($min*60)+($sec))/3600)*-1;
    }
    $_SESSION['GPSLatitude'] = $exifLatitude;
  }
}

function ConvertLngDMStoDEC()
{
  //get the exifdata
  $fDestination = $_SESSION['fileDestination'];
  $exif_data = exif_read_data($fDestination);
  
  //reset the coordinate
  $_SESSION['GPSLongitude'] = "";
  
  //convert the coordinate from dms to decimal
  if($exif_data['GPSLongitude'][0])
  {
    $rawDeg = $exif_data['GPSLongitude'][0];
    $rawMin = $exif_data['GPSLongitude'][1];
    $rawSec = $exif_data['GPSLongitude'][2];

    $degExploded = explode('/', $rawDeg);
    $minExploded = explode('/', $rawMin);
    $secExploded = explode('/', $rawSec);

    $deg = $degExploded[0];
    $min = $minExploded[0];
    $sec = $secExploded[0]/$secExploded[1];

    //get the Longitude Reference
    $exifLongitudeRef = $exif_data['GPSLongitudeRef'];
    
    //Longitude Reference determines the conversion
    if($exifLongitudeRef != 'W')
    {
      $exifLongitude = $deg+((($min*60)+($sec))/3600);
    }
    else
    {
      $exifLongitude = $deg+((($min*60)+($sec))/3600)*-1;
    }    
    $_SESSION['GPSLongitude'] = $exifLongitude;
  }
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

    //go to profile button
    echo "<tr>";
    echo "<td>";
    echo "<button class='button button-block' type='submit' name='profile' />Go To Profile Page</button>";
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
    
    //format the selected tags in a way that can used to be added to the database
    $finalTags = preg_replace("/[^a-zA-Z0-9]+/", "", $selection);

    // print_r($finalTags);
    if(!empty($finalTags))
    {
      echo "<div class='search-text'>";
      echo "The following tags have been added to the image:";
      echo "<br>";
      foreach($finalTags as $selectedTag)
      {
          echo "--".$selectedTag;
          echo "<br>";
          
      }
      echo "<br>";
      echo "You can add more tags by selecting others ";
      echo "<br>";
      echo "or entering another into the custom field and resubmitting";
      echo "<br>";
      echo "Click the 'Go to Profile Page' button to navigate to upload or search pages";

    }
    
    $_SESSION['listOfTags'] = $finalTags;
    $listOfTags = $_SESSION['listOfTags'];
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
    $insertTagSql = "INSERT INTO tags 
    (tag, imageId) 
    VALUES 
    ('$tagValue', '$imageId')";
    
    $dbc->query($insertTagSql);  
  }  
}
?>