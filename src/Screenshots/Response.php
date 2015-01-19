<?php

namespace Alexschwarz89\Browserstack\Screenshots;

/**
 * Class Response
 *
 * Base Class to build a JSON response
 *
 * @package Alexschwarz89\Browserstack\Screenshots
 */
class Response
{

    /**
     * @var
     */
    private $_response;

    /**
     * Sets the response in JSON format
     *
     * @param $apiResponse string JSON
     */
    public function setApiResponse( $apiResponse )
    {
        $this->_response = json_decode($apiResponse,true);
    }

    /**
     * Get the API response as an associative array
     *
     * @return Array
     */
    public function getResponse()
    {
        return $this->_response;
    }

}