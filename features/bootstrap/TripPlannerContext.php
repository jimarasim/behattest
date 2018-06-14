<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
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
     * @Then seattles geocoordinate should appear in the start address textbox
     */
    public function seattlesGeocoordinateShouldAppearInTheStartAddressTextbox()
    {
        Assert::assertEquals($this->tripPlannerPage->seattleOffset["latlon"],$this->tripPlannerPage->getStartAddressText());
    }
    
    /**
     * @Then bellevues geocoordinate should appear in the start address textbox
     */
    public function bellevuesGeocoordinateShouldAppearInTheStartAddressTextbox()
    {
        Assert::assertEquals($this->tripPlannerPage->bellevueOffset["latlon"],$this->tripPlannerPage->getStartAddressText());
    }
}
