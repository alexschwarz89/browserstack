<?php

require __DIR__ . '/../vendor/autoload.php';

/*
 * This a simple example how to get a list of browsers currently supported by Browserstack
 * The given credentials are actually working for that method.
 *
 */

use Alexschwarz89\Browserstack\Screenshots\Api;

$api            = new Api('', '');
$browserList    = $api->getBrowsers();

var_dump( $browserList );