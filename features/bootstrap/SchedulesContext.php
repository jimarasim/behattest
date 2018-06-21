<?php

use Behat\Behat\Context\Context;
use Pages\Home;
use Pages\Schedules;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class SchedulesContext implements Context
{
    private $homePage;
    private $schedulesPage;
    private $route;
    
     /**
     * @Given a route schedule page is navigated to
     */
    public function aRouteSchedulePageIsNavigatedTo()
    {
        $this->schedulesPage = new Schedules(CommonContext::$driver);
        $this->homePage = new Home(CommonContext::$driver);
        
        $this->homePage->open();
        $this->homePage->clickRandomRoute();
    }

    /**
     * @When I click the Parking tab for the route
     */
    public function iClickTheParkingTabForTheRoute()
    {
        $this->schedulesPage->clickParkingTab();
    }

    /**
     * @Then I am taken to the route parking section
     */
    public function iAmTakenToTheRouteParkingSection()
    {
        Assert::assertEquals($this->schedulesPage->getRoutePageTitle(),"Route Parking");
    }

    /**
     * @Then I am shown available parking options for that route
     */
    public function iAmShownAvailableParkingOptionsForThatRoute()
    {
        Assert::assertTrue($this->schedulesPage->getParkingAddressesSize() > 0);
    }

    /**
     * @When I click the Holidays tab for the route
     */
    public function iClickTheHolidaysTabForTheRoute()
    {
        $this->schedulesPage->clickHolidaysTab();
    }

    /**
     * @Then I am taken to the route holidays section
     */
    public function iAmTakenToTheRouteHolidaysSection()
    {
        Assert::assertEquals($this->schedulesPage->getRoutePageTitle(),"Route Holiday Schedule");
    }

    /**
     * @Then am shown upcoming holidays for the route
     */
    public function amShownUpcomingHolidaysForTheRoute()
    {
        Assert::assertTrue($this->schedulesPage->getHolidaysSize() > 0);
    }

    /**
     * @When I click the Map tab for the route
     */
    public function iClickTheMapTabForTheRoute()
    {
        $this->schedulesPage->clickMapTab();
    }

    /**
     * @Then I am taken to the route map section
     */
    public function iAmTakenToTheRouteMapSection()
    {
        Assert::assertEquals($this->schedulesPage->getRoutePageTitle(),"Route Map");
    }

    /**
     * @Then am shown a map for the route
     */
    public function amShownAMapForTheRoute()
    {
        Assert::assertTrue($this->schedulesPage->mapDisplayed());
    }

}
