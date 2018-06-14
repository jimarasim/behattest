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
}
