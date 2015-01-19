<?php
namespace Alexschwarz89\Browserstack\Screenshots;

use Alexschwarz89\Browserstack\Screenshots\Response\Base;
use Alexschwarz89\Browserstack\Screenshots\Response\ScreenshotsResponse;
use tzfrs\Util\Curl;

class Api
{

    /**
     * The BASE URL to the Browserstack Screenshots API where all endpoints are appended
     */
    const API_BASE_URL = 'http://www.browserstack.com/screenshots';

    /**
     * SimpleCurl Instance
     * @var
     */
    protected $curl;

    /**
     * The HTTP Headers used for the requests
     *
     * @var array
     */
    protected $headers = array(
        'Content-type: application/json',
        'Accept: application/json'
    );

    /**
     * If set to true, will output additional debug information
     * to read in the CLI
     *
     * @var bool
     */
    protected $debug  = false;

    /**
     * Browserstack Username
     * @var
     */
    private $username;
    /**
     * Browserstack Password
     * @var
     */
    private $password;

    /**
     * Constructor with Browserstack Username and Password
     *
     * @param $username
     * @param $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->init();
    }

    /**
     * This is actually needed to reset the cURL instance used inside SimpleCurl
     */
    protected function init()
    {
        $this->curl = new Curl();
        $this->setCredentials($this->username, $this->password);
    }

    /**
     * Directly sets HTTP Authentication in cURL instance
     *
     * @param $username
     * @param $password
     */
    protected function setCredentials($username, $password)
    {
        $this->curl->setUserPwd($username, $password);
    }

    /**
     * Returns an Array of valid browser/OS combinations that can be used
     * for requests. This request actually doesn't need Authorization.
     *
     * @return mixed
     */
    public function getBrowsers()
    {
        $res = new Response();
        $res->setApiResponse( $this->_request('browsers.json') );
        return $res->getResponse();
    }

    /**
     * Queries the Status of the jobID provided
     * Gets and parses the <jobid>.JSON file
     *
     *
     * @param $jobId
     * @return ScreenshotsResponse
     */
    public function getJobStatus( $jobId )
    {

        $response = $this->_request($jobId . '.json');
        $screenshotsResponse = new ScreenshotsResponse( $response );

        return $screenshotsResponse;
    }

    /**
     * Actually sends the request to Browserstack
     *
     * @param Request $request
     * @return ScreenshotsResponse|bool
     */
    public function sendRequest(Request $request)
    {
        $params = array();

        foreach ($request as $k=>$v) {
            if ((is_string($v) && strlen($v) > 0) or (is_array($v) && count($v) > 0)) {
                $params[$k] = $v;
            }
        }

        $response = $this->_request(null, $params, 'POST');

        if ($this->debug) {
            var_dump($response);
        }


        if ($response) {
            $screenshotsResponse = new ScreenshotsResponse( $response );
            return $screenshotsResponse;
        }

        return false;

     }


    /**
     * Builds SimpleCurl Requests
     * Internal wrapper function for SimpleCurl
     *
     * Returns the content of the request or false if any error occured
     *
     * @param $params
     * @param string $method
     * @return bool
     */
    private function _request( $endpoint=null, $params=array(), $method='GET' )
    {

        // Init cURL instance
        $this->init();

        // Set default options
        $this->curl->setHTTPHeader( $this->headers );

        $url = self::API_BASE_URL;
        if ($endpoint !== null) {
            $url .= '/' .  $endpoint;
        }
        if (is_array($params) && count($params) > 0) {
            $queryString    = json_encode($params);
        }

        if ($this->debug) {
            print "Request-Url: $url<br>";
            if (isset($queryString)) print "QueryString: $queryString<br>";
        }



        if ($method == 'GET') {
        if (isset($queryString)) $url .= '?' .  $queryString;
            $contents = $this->curl->get($url);

        } elseif($method == 'POST') {
            $contents = $this->curl->post($url, $queryString);
        }

        if ($this->debug) {
            var_dump($contents);
        }


        return ($contents) ? $contents['response'] : false;
    }

    /**
     * Checks if the Browserstack Screenshots Service is online and accessible
     * Can be used prior any requests to ensure that the API is working
     *
     * If anything is fine, returns an Array with index 'success'
     * If any error occured contains an index 'errors' with an error message
     *
     * @return Array
     */
    public function isBrowserstackAccessable()
    {
        $message    = null;
        $headers    = get_headers(self::API_BASE_URL);
        $response = new Base();
        if (!empty($headers) && isset($headers[0])){
            $httpCode = $headers[0];
            preg_match('#HTTP\/1\.1\s+(.*?)\s+#', $httpCode, $matches);
            if (!empty($matches) && isset($matches[1])){
                $httpCode = $matches[1];
                http_response_code($httpCode);
                switch (substr($httpCode, 0, 1)){
                    case 2:
                        $response->setApiResponse(['success' => ['Service is available']]);
                        return $response->getResponse();
                    case 4:
                        if ($httpCode == 404 ) $message = 'API Endpoint not found';
                        if ($httpCode == 401 ) $message = 'User is not authorized';
                        if ($httpCode == 401 ) $message = 'Bad request';
                        break;
                    case 5:
                        $message = 'Bad request: ' . $httpCode;
                        break;
                }
            }
        } else {
            $message = 'Could not establish connection to Browserstacks';
        }

        $response->setApiResponse(['errors' => [$message]]);
        return $response->getResponse();
    }


}