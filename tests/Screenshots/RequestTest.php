<?php

use \Alexschwarz89\Browserstack\Screenshots\Request;

class RequestTest extends PHPUnit_Framework_TestCase
{
    protected static $browserstackApi;

    public static function setUpBeforeClass()
    {

    }

    public function testAddBrowser()
    {
        $request = new Request();
        $request->addBrowser(
            'Windows',
            '8.1',
            'ie',
            '11.0'
        );

        $this->assertArrayHasKey('os', $request->browsers[0]);
        $this->assertArrayHasKey('os_version', $request->browsers[0]);
        $this->assertArrayHasKey('browser', $request->browsers[0]);
        $this->assertArrayHasKey('browser_version', $request->browsers[0]);

        $this->assertEquals('Windows', $request->browsers[0]['os']);
        $this->assertEquals('8.1', $request->browsers[0]['os_version']);
        $this->assertEquals('ie', $request->browsers[0]['browser']);
        $this->assertEquals('11.0', $request->browsers[0]['browser_version']);
    }

    public function testBuildRequest()
    {
        $request = Request::buildRequest('http://www.example.org', 'Windows', '8.1', 'ie', '11.0');

        $this->assertInstanceOf('\Alexschwarz89\Browserstack\Screenshots\Request', $request);

    }
}