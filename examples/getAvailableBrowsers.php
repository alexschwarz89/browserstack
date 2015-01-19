<?php

require __DIR__ . '/../vendor/autoload.php';

/*
 * This a simple example how to get a list of browsers currently supported by Browserstack
 * The given credentials are actually working for that method.
 *
 */

$api            = new Alexschwarz89\Browserstack\Screenshots\Api('', '');
$browserList    = $api->getBrowsers();

var_dump( $browserList );