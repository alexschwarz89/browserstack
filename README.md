Browserstack
============
[![Latest Stable Version](https://poser.pugx.org/alexschwarz89/browserstack/v/stable)](https://packagist.org/packages/alexschwarz89/browserstack)

An easy-to-use PHP library for the Browserstack Screenshots API. Working examples included.

## Install

Install via [composer](https://getcomposer.org):

```javascript
{
    "require": {
        "alexschwarz89/browserstack": "0.0.2"
    }
}
```

Run `composer install`.

## Example usage

#### Get an array of available browsers

```php
use Alexschwarz89\Browserstack\Screenshots\Api;
$api         = new Api('username', 'password');
$browserList = $api->getBrowsers();
```

#### Generate a screenshot
```php
use Alexschwarz89\Browserstack\Screenshots\Api;
use Alexschwarz89\Browserstack\Screenshots\Request;
$api        = new Api('account', 'password');
$request    = Request::buildRequest('http://www.example.org', 'Windows', '8.1', 'ie', '11.0');
$response   = $api->sendRequest( $request );
$jobId      = $response->jobId;
```

#### Query information about the request

```php
$status = $api->getJobStatus('browserstack_jobid');
if ($status->isFinished()) {
  foreach ($status->finishedScreenshots as $screenshot) {
    print $screenshot->image_url ."\n";
  }
}
```
