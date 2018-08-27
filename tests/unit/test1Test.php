<?php
require '/Library/WebServer/Documents/project/tests/sum1.php';
// require '/Library/WebServer/Documents/project/pages/include/uploadFunctions.php';

use PHPUnit\Framework\TestCase;

class test1Test extends TestCase
{
    public function testTrueAssertsToTruex()
    {
        $this->assertTrue(true);
    }

    public function testSumHappyx()
    {
        $a = 7;
        $this-> assertTrue(sum1(3,4) == $a);
    }

    public function testSumSadx()
    {
        $a = 7;
        $this-> assertFalse(sum1(3,2) == $a);
    }

    public function testSumSad()
    {
        $a = 7;
        $this-> assertFalse(sum1(3,2) == $a);
    }
}
?>