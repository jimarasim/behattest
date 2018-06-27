<?php

use Behat\Behat\Context\Context;
use Pages\Home;
use Pages\Schedules;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class HomepageContext implements Context
{
    private $homePage;
    private $tripPlannerPage;
    private $riderAlertsPage;
    private $route;
    

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
        Assert::assertTrue($this->homePage->homePageButtonDisplayed());  
    }

    /**
     * @Given the home page button is enabled
     */
    public function theHomePageButtonIsEnabled()
    {
        Assert::assertTrue($this->homePage->homePageButtonEnabled());
    }

    /**
     * @When I click the home page button
     */
    public function iClickTheHomePageButton()
    {
        $this->homePage->clickHomePageButton();
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
        Assert::assertTrue($this->homePage->soundTransitLogoDisplayed());
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
        Assert::assertTrue($this->homePage->startAddressTextboxEnabled());
    }

    /**
     * @Given the End Destination text field is enabled
     */
    public function theEndDestinationTextFieldIsEnabled()
    {
        Assert::assertTrue($this->homePage->endAddressTextboxEnabled());
    }

    /**
     * @Given the Plan Trip button is enabled
     */
    public function thePlanTripButtonIsEnabled()
    {
        Assert::assertTrue($this->homePage->planTripButtonEnabled());
    }

    /**
     * @When I specify :arg1 as the Start Destination
     */
    public function iSpecifyAsTheStartDestination($arg1)
    {
        $this->homePage->enterStartAddress($arg1);
    }

    /**
     * @When :arg1 as the End Destination
     */
    public function asTheEndDestination($arg1)
    {
        $this->homePage->enterEndAddress($arg1);
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
        Assert::assertTrue($this->tripPlannerPage->mapSvgDisplayed());
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
        Assert::assertTrue($this->homePage->menuItemWithTextEnabled('Schedules'));
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
        Assert::assertEquals($this->riderAlertsPage->getPageTitleHeaderText(),'Rider Alerts');
    }
    
        /**
     * @Given top-level menu item :arg1 is enabled
     */
    public function topLevelMenuItemIsEnabled($arg1)
    {
        Assert::assertTrue($this->homePage->menuItemWithTextEnabled($arg1));
    }

    /**
     * @When I click the :arg1
     */
    public function iClickThe($arg1)
    {
        $this->homePage->clickMenuItemWithText($arg1);
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
        Assert::assertEquals($this->homePage->getPageTitleHeaderText(),$arg1);
    }

    /**
     * @Then the :arg1 should exist
     */
    public function theShouldExist($arg1)
    {
        Assert::assertTrue($this->homePage->xpathElementEnabled($arg1));
    }
    
     /**
     * @When I click any route in the Find Your Schedule dropdown
     */
    public function iClickAnyRouteInTheFindYourScheduleDropdown()
    {
        $this->route = $this->homePage->clickRandomRoute();
    }

    /**
     * @Then I should be taken to its schedule page
     */
    public function iShouldBeTakenToItsSchedulePage()
    {
        $schedulesPage = new Schedules(CommonContext::$driver);
        Assert::assertTrue(strpos($schedulesPage->currentUrl(),$schedulesPage->url()) !== FALSE);
        Assert::assertTrue(strpos($schedulesPage->currentUrl(),$this->route) !== FALSE);
        Assert::assertTrue(strpos($schedulesPage->getRouteName(),$this->route) !== FALSE);
        
    }
    
     /**
     * @Then static page elements should look the same as last time
     */
    public function staticPageElementsShouldLookTheSameAsLastTime()
    {
        Assert::assertTrue($this->homePage->screenShotDiffToFromArea());
        Assert::assertTrue($this->homePage->screenShotDiffBlockSystemMain());
        Assert::assertTrue($this->homePage->screenShotDiffRiderAlertsSubscription());  
        Assert::assertTrue($this->homePage->screenShotDiffFooterDiv()); 
    }
}
