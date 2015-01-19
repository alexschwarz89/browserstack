<?php

namespace Alexschwarz89\Browserstack\Screenshots\Response;

/**
 * Class Base
 * @package Alexschwarz89\Browserstack\Screenshots\Response
 */
class Base {

    public $response;

    /**
     * @param $apiResponse
     */
    public function setApiResponse( $apiResponse )
    {
        $this->response = $apiResponse;
    }

    /**
     * @return mixed
     */
    public function  getResponse()
    {
        return $this->response;
    }

}