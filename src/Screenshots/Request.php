<?php

namespace Alexschwarz89\Browserstack\Screenshots;

/**
 * Class Request
 *
 * A simple Class to build up Screenshot Requests
 *
 * @package Alexschwarz89\Browserstack\Screenshots
 */
class Request
{

    /**
     * URL of the Website you want to make a screenshot from
     * Example: http://www.example.com
     * @var
     */
    public $url;
    /* Optional parameter that is needed only for Screenshots on mobile devices
     * @var
     */
    public $device;
    /**
     * Optional parameter that is needed only for Screenshots on mobile devices
     * Possible values: portrait, landscape
     * Default: portrait
     *
     * @var string
     */
    public $orientation = 'portrait';
    /**
     * The Resolution screenshots on OSX-based systems are generated
     * Values: 1024x768, 1280x960, 1280x1024, 1600x1200, 1920x1080
     *
     * @var string
     */
    public $mac_res = '';
    /**
     * The Resolution screenshots on Windows-based systems are generated
     * Values: 1024x768, 1280x1024
     *
     * @var string
     */
    public $win_res = '';
    /**
     * The Quality of the generated screenshot
     * Possible values: compressed, original
     *
     * @var string
     */
    public $quality = '';
    /**
     * Required if the page is local and that a Local Testing connection has been set up.
     *
     * @var string
     */
    public $local = '';
    /**
     * Required if specifying the time (in seconds) to wait before taking the screenshot.
     *
     * @var int
     */
    public $wait_time = 2;
    /**
     * Required if results are to be sent back to a public URL
     *
     * @var string
     */
    public $callback_url = '';

    /**
     * An array containing arrays of os/browser combinations
     *
     * @var array
     */
    public $browsers = array();

    /**
     * Adds a OS/Browser-Combination to the list of requested browsers
     * Optional parameter device is required for mobile devices
     *
     * @param $os
     * @param $os_version
     * @param $browser
     * @param $browser_version
     * @param null $device
     */
    public function addBrowser($os, $os_version, $browser, $browser_version, $device = null)
    {
        $this->browsers[] = array(
            'os' => $os,
            'os_version' => $os_version,
            'browser' => $browser,
            'browser_version' => $browser_version,
            'device' => $device
        );
    }

    /**
     * Short-hand function to build a request with a single browser/OS combination
     *
     * @param $url
     * @param $os
     * @param $os_version
     * @param $browser
     * @param $browser_version
     * @return Request
     */
    public static function buildRequest($url, $os, $os_version, $browser, $browser_version)
    {
        $request = new self;
        $request->url = $url;
        $request->addBrowser($os, $os_version, $browser, $browser_version);

        return $request;
    }

}