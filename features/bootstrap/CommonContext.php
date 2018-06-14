<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterStepScope;
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
    
    /** @AfterStep */
    public static function afterStep(AfterStepScope $scope)
    {
        //TAKE SCREENSHOT IF STEP FAILED
        if(!$scope->getTestResult()->isPassed()) {
            $screenshotpath = getcwd().'/screenshots/'.date('YmdHis').'.png';
            print('FAIL:'.$scope->getStep()->getText().' SCREENSHOT:'.$screenshotpath);
            
            CommonContext::$driver->takeScreenshot($screenshotpath);
        }
    }
    
    /**
     * @AfterScenario
     */
    public static function after()
    {
        CommonContext::$driver->quit();
    }
}
