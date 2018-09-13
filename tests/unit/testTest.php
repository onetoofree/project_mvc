<?php
require '/Library/WebServer/Documents/project/tests/sum.php';
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
}
?>