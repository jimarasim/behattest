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
    private $homePage;
    private $tripPlannerPage;
    private $riderAlertsPage;
    

    /**
     * @Given https:\/\/soundtransit.org is navigated to
     */
    public function httpsSoundtransitOrgIsNavigatedTo()
    {
        $this->homePage = new Home(CommonContext::$driver);
        
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
        Assert::assertEquals($this->homePage->currentUrl(),$this->homePage->url());  
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
        Assert::assertContains('/Trip-Planner', $this->homePage->currentUrl());
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
        $this->tripPlannerPage->waitForMapToFinishLoading();
    }
    
    /**
     * @Given the Schedules Menu is enabled
     */
    public function theSchedulesMenuIsEnabled()
    {
        Assert::assertTrue($this->homePage->getMenuItemWithTextContaining('Schedules')->isEnabled());
    }


    /**
     * @When I click the Alerts menu item
     */
    public function iClickTheAlertsMenuItem()
    {
        $this->riderAlertsPage = $this->homePage->clickRiderAlertsMenuItem();
    }

    /**
     * @Then the rider-alerts page is displayed
     */
    public function theRiderAlertsPageIsDisplayed()
    {
        Assert::assertEquals($this->riderAlertsPage->pageTitleHeader()->getText(),'Rider Alerts');
    }
    
        /**
     * @Given top-level menu item :arg1 is enabled
     */
    public function topLevelMenuItemIsEnabled($arg1)
    {
        Assert::assertTrue($this->homePage->getMenuItemWithTextContaining($arg1)->isEnabled());
    }

    /**
     * @When I click the :arg1
     */
    public function iClickThe($arg1)
    {
        $this->homePage->getMenuItemWithTextContaining($arg1)->click();
    }

    /**
     * @Then the :arg1 should be navigated to
     */
    public function theShouldBeNavigatedTo($arg1)
    {
        Assert::assertEquals($this->homePage->currentUrl(),$arg1);
    }

    /**
     * @Then the page title should be :arg1
     */
    public function thePageTitleShouldBe($arg1)
    {
        Assert::assertEquals($this->homePage->pageTitleHeader()->getText(),$arg1);
    }

    /**
     * @Then the :arg1 should exist
     */
    public function theShouldExist($arg1)
    {
        Assert::assertTrue($this->homePage->xpathElementEnabled($arg1));
    }
}
