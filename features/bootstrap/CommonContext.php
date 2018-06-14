<?php

use Behat\Behat\Context\Context;
use Utilities\WebDriverFactory;

/**
 * Description of CommonContext
 *
 * @author jameskarasim
 */
class CommonContext implements Context{
    public static $driver;
    
    /**
     * @Given an open chrome browser
     */
    public static function anOpenChromeBrowser()
    {
        CommonContext::$driver = WebDriverFactory::getDriver('chrome');
        
    }
    
    /**
     * @AfterScenario
     */
    public static function after()
    {
        CommonContext::$driver->quit();
    }
}
