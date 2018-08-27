<?php
require '/Library/WebServer/Documents/project/tests/sum.php';
require '/Library/WebServer/Documents/project/pages/include/uploadFunctions.php';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/Library/WebServer/Documents/project/apiKey.json');

use PHPUnit\Framework\TestCase;

class testTest extends TestCase
{
    public function testTrueAssertsToTrue()
    {
        $this->assertTrue(true);
    }

    public function testSumHappy()
    {
        $a = 7;
        $this-> assertTrue(sum(3,4) == $a);
    }

    public function testSumSad()
    {
        $a = 7;
        $this-> assertFalse(sum(3,2) == $a);
    }

    public function testSumSad1()
    {
        $a = 2;
        $this-> assertFalse(sum(3,2) == $a);
    }

    public function testImageRecognition()
    {
        $imageFile = '/Library/WebServer/Documents/project/uploads/Beach.jpg';
        $this-> assertTrue(is_string(getVisionTags($imageFile)) == true);
        // $a = 2;
        // $this-> assertFalse(sum(3,2) == $a);
    }

    // public function testReadExif()
    // {
    //     //file with exif - details returned
    //     //file without exif - nothing returned

        
    // }

    // public function testdisplayTags()
    // {
    //     //

        
    // }

    
}
?>