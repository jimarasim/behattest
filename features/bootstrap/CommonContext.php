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
    public static $browser;
    
    public function __construct($browser) {
        $this->browser = $browser;
    }
    
    /**
     * @Given an open browser
     */
    public function anOpenChromeBrowser()
    {
        CommonContext::$driver = WebDriverFactory::getDriver($this->browser);       
    }
    
    /** @AfterStep */
    public function afterStep(AfterStepScope $scope)
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
    public function after()
    {
        CommonContext::$driver->quit();
    }
}
