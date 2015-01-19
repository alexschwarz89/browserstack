<?php

use \Alexschwarz89\Browserstack\Screenshots\Response;

class ResponseTest extends PHPUnit_Framework_TestCase
{
    public function testSetApiResponse()
    {
        $response = new Response();

        $myArray = array("foo" => "bar");

        $response->setApiResponse( json_encode($myArray) );

        $this->assertEquals($myArray, $response->getResponse());
    }
}