<?php

namespace Utilities;

use Facebook\WebDriver\Chrome\ChromeDriver;

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
        } else {
            throw new Exception('INVALID BROWSER SPECIFIED');
        }
        
        return $driver;
    }
    
}
