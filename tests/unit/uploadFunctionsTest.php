<?php
require '/Library/WebServer/Documents/project/tests/sum1.php';
require '/Library/WebServer/Documents/project/pages/include/uploadFunctions.php';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/Library/WebServer/Documents/project/apiKey.json');

use PHPUnit\Framework\TestCase;

class thumbnailTest extends TestCase
{
    public function testTrueAssertsToTruex()
    {
        $this->assertTrue(true);
    }

    public function testThumbnailCreated()
    {
        createThumbnail('Beach.jpg');
        $this->assertFileExists('../uploads/thumbnails/Beach.jpg');
        unlink('../uploads/thumbnails/Beach.jpg');

    }

    public function testImageRecognition()
    {
        $imageFile = '/Library/WebServer/Documents/uploads/Beach.jpg';
        $tags = getVisionTags($imageFile);
        $this-> assertContains('sea', $tags);
        $this-> assertContains('sky', $tags);
    }

    public function testReadExifFromUploadedImages()
    {
        $imageFile = '/Library/WebServer/Documents/uploads/Hall.jpg';
        $exif = readExifFromUploadedImages($imageFile);
        // echo 'Makeeee';
        // echo $_SESSION['Make'];
        // echo 'Makeeeer';
        $this->assertTrue($_SESSION['Make'] == 'NIKON CORPORATION');
        $this->assertTrue($_SESSION['Model'] == 'NIKON D300S');
        $this->assertTrue($_SESSION['ExposureTime'] == '1/250');
        $this->assertTrue($_SESSION['ApertureFNumber'] == 'f/2.8');
        $this->assertTrue($_SESSION['ISOSpeedRatings'] == '800');
        $this->assertTrue($_SESSION['XResolution'] == '300dpi');
    }

    
}
?>