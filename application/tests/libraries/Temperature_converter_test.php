<?php
class Temperature_converter_test extends TestCase
{
    public function test_FtoC()
    {
        $obj = new Temperature_converter();
        $actual = $obj->FtoC(100);
        $expected = 37.0;
        $this->assertEquals($expected, $actual, '', 1);
    }
}