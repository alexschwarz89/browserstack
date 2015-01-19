<?php

use \Alexschwarz89\Browserstack\Screenshots\Api;
use \Alexschwarz89\Browserstack\Screenshots\Request;

class ApiTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Api
     */
    protected static $browserstackApi;

    public static function setUpBeforeClass()
    {
        self::$browserstackApi = new Api('phpunit', 'phpunit');
    }

    public function testConstruct()
    {
        $this->assertInstanceOf('\Alexschwarz89\Browserstack\Screenshots\Api', self::$browserstackApi);
    }

    public function testGetBrowsers()
    {
        $response = self::$browserstackApi->getBrowsers();

        // Check if we have a valid JSON response
        $this->assertInternalType('array', $response );
    }

    public function testGetJobStatus()
    {
        $this->assertInstanceOf('Alexschwarz89\Browserstack\Screenshots\Response\ScreenshotsResponse', self::$browserstackApi->getJobStatus('123'));
    }

    public function testSendRequest()
    {
        $request = Request::buildRequest(
            'http://www.example.com',
            'Windows',
            '8.1',
            'ie',
            '11.0'
        );

        $this->assertInstanceOf('Alexschwarz89\Browserstack\Screenshots\Request', $request);

        $response = self::$browserstackApi->sendRequest( $request );

        $this->assertInstanceOf('Alexschwarz89\Browserstack\Screenshots\Response\ScreenshotsResponse', $response);


        return false;
    }

    public function testIsBrowserstackAccessable()
    {
        $response = self::$browserstackApi->isBrowserstackAccessable();

        $result = false;

        if (is_array($response) && (isset($response['success']) || isset($response['errors']))) {
            $result = true;
        }

        $this->assertTrue( $result );
    }


}