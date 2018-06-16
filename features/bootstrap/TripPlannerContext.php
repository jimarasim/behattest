<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Pages\TripPlanner;
use PHPUnit\Framework\Assert;


/**
 * Description of TripPlannerContext
 *
 * @author jameskarasim
 */
class TripPlannerContext implements Context {
    private $tripPlannerPage;
    
    /**
     * @Given https:\/\/soundtransit.org\/tripplanner is navigated to
     */
    public function httpsSoundtransitOrgTripplannerIsNavigatedTo()
    {
        $this->tripPlannerPage = new TripPlanner(CommonContext::$driver);
        
        $this->tripPlannerPage->open();
    }

    /**
     * @Given I set the start address to :arg1
     */
    public function iSetTheStartAddressTo($arg1)
    {
        $this->tripPlannerPage->enterStartAddress($arg1);
    }

    /**
     * @Given the end address to :arg1
     */
    public function theEndAddressTo($arg1)
    {
        $this->tripPlannerPage->enterEndAddress($arg1);
    }

    /**
     * @When I click the plan trip button
     */
    public function iClickThePlanTripButton()
    {
        $this->tripPlannerPage->clickPlanTripButton();
    }

    
    /**
     * @Then a route is displayed
     */
    public function aRouteIsDisplayed()
    {
        Assert::assertTrue($this->tripPlannerPage->tripResultSummaryTableEnabled());
    }
    
     /**
     * @Given I context click the map on seattle
     */
    public function iContextClickTheMapOnSeattle()
    {
        $this->tripPlannerPage->contextClickMapOnSeattle();
    }
    
    /**
     * @Given I context click the map on bellevue
     */
    public function iContextClickTheMapOnBellevue()
    {
        $this->tripPlannerPage->contextClickMapOnBellevue();
    }

    /**
     * @When I click Start Trip Here
     */
    public function iClickStartTripHere()
    {
        $this->tripPlannerPage->clickMapStartTripHereFromContextClick();
    }
    
    /**
     * @When click End Trip Here
     */
    public function clickEndTripHere()
    {
        $this->tripPlannerPage->clickMapEndTripHereFromContextClick();
    }
    
    /**
     * @Then the map should load a route
     */
    public function theMapShouldLoadARoute()
    {
        $this->tripPlannerPage->waitForMapToStartLoading();
        $this->tripPlannerPage->waitForMapToFinishLoading();
    }

    /**
     * @Then a geocoordinate should appear in the start address textbox
     */
    public function aGeocoordinateShouldAppearInTheStartAddressTextbox()
    {
        Assert::assertTrue($this->tripPlannerPage->startAddressMatchesGeocode());
    }
    
        /**
     * @Given I havent entered a start address
     */
    public function iHaventEnteredAStartAddress()
    {
        $this->tripPlannerPage->clearStartAddress();
    }

    /**
     * @Given I havent entered an end address
     */
    public function iHaventEnteredAnEndAddress()
    {
        $this->tripPlannerPage->clearEndAddress();
    }

    /**
     * @Then an alert pops up saying:
     */
    public function anAlertPopsUpSaying(PyStringNode $string)
    {
        $this->tripPlannerPage->waitForAlert();
        
        $message = $this->tripPlannerPage->getAlertMessage();
        
        Assert::assertTrue(strpos($message,(string)$string)!==FALSE);
    }

    /**
     * @Then alert can be dismissed by clicking ok
     */
    public function alertCanBeDismissedByClickingOk()
    {
        $this->tripPlannerPage->acceptAlert();
        
        //check for an element on the page to verify the alert has closed
        Assert::assertTrue($this->tripPlannerPage->startAddressTextboxEnabled());
    }
    
     /**
     * @Then I see a We're Sorry message
     */
    public function iSeeAWereSorryMessage()
    {
        $this->tripPlannerPage->waitForWereSorryHeader();
    }
}
