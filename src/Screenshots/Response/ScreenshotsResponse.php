<?php

namespace Alexschwarz89\Browserstack\Screenshots\Response;

class ScreenshotsResponse extends Base
{

    const ERROR_LIMIT_REACHED           = 'Paralell limit reached';
    const ERROR_INVALID_REQUEST         = 'Invalid Request';
    const ERROR_VALIDATION_FAILED       = 'Validation failed';
    const ERROR_AUTHENTICATION_FAILED   = 'Authentication failed. Please check your login details and retry.';

    // Validation failed (Contains extra Array errors with objects code and field.


    /**
     * Contains an Array of finished screenshots
     *
     * @var
     */
    public $finishedScreenshots;
    /**
     * Contains an Array of pending screenshots
     *
     * @var
     */
    public $pendingScreenshots;
    /**
     * Contains an Array of failed screenshots
     *
     * @var
     */
    public $failedScreenshots;

    /**
     * Indicates if the request was successful
     * Should be used first
     *
     * A not finished Request is also a successful.
     *
     * @var bool
     */
    public  $isSuccessful = false;
    /**
     * If the request was successful contains the Browserstack Job ID
     * needed to query the JOB status
     *
     * @var bool
     */
    public  $jobId        = false;

    /**
     * If this is true, the parallel limit was reached and the request has not been made
     *
     * @var bool
     */
    public  $isThrottled  = false;
    /**
     * If provided, contains the Error Message from Browserstack
     *
     * @var bool
     */
    public  $errorMessage = false;

    /**
     * If provided, contains the fields that caused an error at Browserstack
     * @var array
     */
    public  $errorFields  = [];

    public function __construct( $apiResponse )
    {
        parent::setApiResponse( $apiResponse );
        $this->parse();
    }

    /**
     * Parse the JSON response from Browserstack
     *
     * @return bool
     */
    public function parse()
    {
        if (is_string($this->response) && $this->response === self::ERROR_AUTHENTICATION_FAILED ) {
            $this->errorMessage = self::ERROR_AUTHENTICATION_FAILED;
        }

        $this->response = json_decode( $this->response );

        if (!$this->response) {
            return false;
        }

        $this->finishedScreenshots = array();
        $this->pendingScreenshots  = array();



        if (isset($this->response->message)) {
            if ($this->response->message === 'Parallel limit reached') {
                $this->isThrottled = true;
            }

            $this->errorMessage = $this->response->message;
            if (isset($this->response->errors)) {
                $this->errorFields = $this->response->errors;
            }
        }
        if (isset($this->response->screenshots) && count($this->response->screenshots) > 0) {
            $this->isSuccessful = true;

            foreach ($this->response->screenshots as $screenshot) {
                if ($screenshot->state == 'done') {
                    $this->finishedScreenshots[] = $screenshot;
                } elseif($screenshot->state == 'pending' || $screenshot->state == 'processing') {
                    $this->pendingScreenshots[] = $screenshot;
                } elseif($screenshot->state == 'timed-out' || $screenshot->state == 'failed') {
                    $this->failedScreenshots[]= $screenshot;
                }
            }
        }
        if (isset($this->response->job_id)) {
            $this->jobId = $this->response->job_id;
        } elseif(isset($this->response->id)) {
            $this->jobId = $this->response->id;
        } else {
            $this->isSuccessful = false;
            return false;
        }

        return $this->jobId;

    }

    /**
     * If there are no pending screenshots the request is considered as finished
     *
     * @return bool
     */
    public function isFinished()
    {
        if (isset($this->response) && count($this->pendingScreenshots) == 0 ) {
            return true;
        }

        return false;
    }

}