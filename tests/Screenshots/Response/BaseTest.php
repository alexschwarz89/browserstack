<?php

use Alexschwarz89\Browserstack\Screenshots\Response\Base;

class BaseTest extends PHPUnit_Framework_TestCase
{
    public function testSetApiResponse()
    {
        $response = new Base();
        $myArray = array("foo" => "bar");

        $myObject = new StdClass();
        $myObject->foo = "bar";

        $response->setApiResponse( json_encode($myArray) );
        $this->assertEquals($myObject , $response->getResponse());
    }

    public function testGetResponse()
    {
        $response = new Base();

        $myArray = array("foo" => "bar");
        $response->response = $myArray;

        $this->assertEquals($myArray, $response->getResponse());
    }
}