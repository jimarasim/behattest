<?php

namespace Utilities;

use Exception;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

/**
 * Description of WebDriverFactory
 *
 * @author jameskarasim
 */
class WebDriverFactory {
    
    public static $driver;
    
    public static function getDriver($browser) {
        if ($browser === 'chrome') {
            putenv("webdriver.chrome.driver=selenium/chromedriver");
            $driver = ChromeDriver::start();
        } elseif ($browser === 'firefox') {
            //NOTE: FIREFOX must be run with selenium grid version 3.8.1 because it requires -enablePassThrough false, which isn't available on later versions of grid
            $host = 'http://localhost:4444/wd/hub';
            $capabilities = DesiredCapabilities::firefox();
            $driver = RemoteWebDriver::create($host, $capabilities);         
        } else {
            throw new Exception('INVALID BROWSER SPECIFIED');
        }
        
        return $driver;
    }
    
}
