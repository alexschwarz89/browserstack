<?php

require __DIR__ . '/../vendor/autoload.php';

/*
 * This a simple example for a complete process-implementation
 *  generating a screenshot
 *  querying the information
 *  receive a list of generated screenshots
 *
 */

use Alexschwarz89\Browserstack\Screenshots\Api;
use Alexschwarz89\Browserstack\Screenshots\Request;

const BROWSERSTACK_ACCOUNT   = '';
const BROWSERSTACK_PASSWORD  = '';

$api    = new Api(BROWSERSTACK_ACCOUNT, BROWSERSTACK_PASSWORD);

// Short-hand Notation
$request    = Request::buildRequest('http://www.example.org', 'Windows', '8.1', 'ie', '11.0');

// Send the request
$response   = $api->sendRequest( $request );

// Query information about the newly created request
if ($response->isSuccessful) {
    // Wait until the request is finished
    do {
        // Query Job Status
        $status = $api->getJobStatus($response->jobId);
        if ($status->isFinished()) {
            // When it's finished, print out the image URLs
            foreach ($status->finishedScreenshots as $screenshot) {
                print $screenshot->image_url ."\n";
            }
            break;
        }
        // Wait five seconds
        sleep(5);

    } while (true);

} else {
    print 'Job creation failed. Reason: ' . $response->errorMessage . "\n";
}