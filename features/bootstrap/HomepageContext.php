<?php

use Behat\Behat\Context\Context;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Pages\Home;
use Pages\TripPlanner;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class HomepageContext implements Context
{
    private $driver;
    private $homePage;
    private $tripPlannerPage;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        putenv("webdriver.chrome.driver=selenium/chromedriver");
    }
    
    /**
     * @Given an open chrome browser
     */
    public function anOpenChromeBrowser()
    {
        
        $this->driver = ChromeDriver::start();
    }

    /**
     * @Given https:\/\/soundtransit.org is navigated to
     */
    public function httpsSoundtransitOrgIsNavigatedTo()
    {
        $this->homePage = new Home($this->driver);
        $this->homePage->open();
    }

    /**
     * @Given the home page button is visible
     */
    public function theHomePageButtonIsVisible()
    {        
        Assert::assertTrue($this->homePage->homePageButton()->isDisplayed());  
    }

    /**
     * @Given the home page button is enabled
     */
    public function theHomePageButtonIsEnabled()
    {
        Assert::assertTrue($this->homePage->homePageButton()->isEnabled());
    }

    /**
     * @When I click the home page button
     */
    public function iClickTheHomePageButton()
    {
        $this->homePage->homePageButton()->click();
    }

    /**
     * @Then the url should be https:\/\/www.soundtransit.org
     */
    public function theUrlShouldBeHttpsWwwSoundtransitOrg()
    {
        Assert::assertEquals($this->driver->getCurrentURL(),$this->homePage->url());  
    }

    /**
     * @Then the sound transit logo should appear
     */
    public function theSoundTransitLogoShouldAppear()
    {        
        Assert::assertTrue($this->homePage->soundTransitLogo()->isDisplayed());
    }

    /**
     * @Then :arg1 should be displayed
     */
    public function shouldBeDisplayed($arg1)
    {
        Assert::assertTrue($this->homePage->textDisplayed($arg1));
    }
    
     /**
     * @Given the Start Destination text field is enabled
     */
    public function theStartDestinationTextFieldIsEnabled()
    {
        Assert::assertTrue($this->homePage->startAddressTextbox()->isEnabled());
    }

    /**
     * @Given the End Destination text field is enabled
     */
    public function theEndDestinationTextFieldIsEnabled()
    {
        Assert::assertTrue($this->homePage->endAddressTextbox()->isEnabled());
    }

    /**
     * @Given the Plan Trip button is enabled
     */
    public function thePlanTripButtonIsEnabled()
    {
        Assert::assertTrue($this->homePage->planTripButton()->isEnabled());
    }

    /**
     * @When I specify :arg1 as the Start Destination
     */
    public function iSpecifyAsTheStartDestination($arg1)
    {
        $this->homePage->startAddressTextbox()->sendKeys($arg1);
    }

    /**
     * @When :arg1 as the End Destination
     */
    public function asTheEndDestination($arg1)
    {
        $this->homePage->endAddressTextbox()->sendKeys($arg1);
    }

    /**
     * @When click the Plan Trip button
     */
    public function clickThePlanTripButton()
    {
        $this->tripPlannerPage = $this->homePage->clickPlanTripButton();
    }

    /**
     * @Then the url should end with \/Trip-Planner
     */
    public function theUrlShouldEndWithTripPlanner()
    {
        Assert::assertContains('/Trip-Planner', $this->driver->getCurrentUrl());
    }

    /**
     * @Then a map should be displayed
     */
    public function aMapShouldBeDisplayed()
    {
        Assert::assertTrue($this->tripPlannerPage->mapSvg()->isDisplayed());
    }

    /**
     * @Then the route should finish loading
     */
    public function theRouteShouldFinishLoading()
    {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loading'))
        );
    }
    
        /**
     * @Given the Schedules Menu is enabled
     */
    public function theSchedulesMenuIsEnabled()
    {
        Assert::assertTrue($this->homePage->getMenuItemWithTextContaining('Schedules')->isEnabled());
    }

    /**
     * @When I move the mouse over the Schedules Menu
     */
    public function iMoveTheMouseOverTheSchedulesMenu()
    {
        $this->driver->action()->moveToElement($this->homePage->getMenuItemWithTextContaining('Schedules'))->perform();     
    }

    /**
     * @When the menu popup appears
     */
    public function theMenuPopupAppears()
    {
        $this->homePage->waitForDropdownMenu();
    }

    /**
     * @When I select the Alerts menu item
     */
    public function iSelectTheAlertsMenuItem()
    {
        $this->homePage->getMenuItemWithHrefContaining('rider-alerts')->click();
    }

    /**
     * @Then the rider-alerts page is displayed
     */
    public function theRiderAlertsPageIsDisplayed()
    {
        $pagetitle = $this->driver->findElement(WebDriverBy::id('page-title'));
        
        Assert::assertEquals($pagetitle->getText(),'Rider Alerts');
    }
    
    /**
     * @AfterScenario
     */
    public function after()
    {
        if($this->driver) {
            $this->driver->quit();
        }
    }
}
