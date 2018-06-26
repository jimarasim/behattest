<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;
use Utilities\Screenshot;

/**
 * Description of Schedules
 *
 * @author jameskarasim
 */
class Schedules extends Page {
    public function url() {return 'https://www.soundtransit.org/schedules/';}
    
    //elements
    private function routePageTitle() {return $this->driver->findElement(WebDriverBy::cssSelector('h1.route-page-title'));}
    private function routeNameSpan() {return $this->driver->findElement(WebDriverBy::cssSelector('span.route-name'));}   
    private function parkingTab() {return $this->driver->findElement(WebDriverBy::xpath('//ul[@id="route-content-menu"]/li/a[contains(text(),"Parking")]'));}
    private function parkingAddressDivs() {return $this->driver->findElements(WebDriverBy::cssSelector('div.address-group'));}
    private function holidaysTab() {return $this->driver->findElement(WebDriverBy::xpath('//ul[@id="route-content-menu"]/li/a[contains(text(),"Holidays")]'));}
    private function holidayListItems() {return $this->driver->findElements(WebDriverBy::cssSelector('li.holiday-service-item'));}
    private function mapTab() {return $this->driver->findElement(WebDriverBy::xpath('//ul[@id="route-content-menu"]/li/a[contains(text(),"Map")]'));}
    private function map() {return $this->driver->findElement(WebDriverBy::id('map'));}
    private function mainDiv() {return $this->driver->findElement(WebDriverBy::id('main'));}
    
    //state checks
    public function mapDisplayed() {
        return $this->map()->isDisplayed();
    }
    
    //clicks
    public function clickParkingTab() {
        $this->parkingTab()->click();
        $this->waitForUrlContains("parking");
    }
    
    public function clickHolidaysTab() {
        $this->holidaysTab()->click();
        $this->waitForUrlContains("holidays");
    }
    
    public function clickMapTab() {
        $this->mapTab()->click();
        $this->waitForUrlContains("map");
    }
    
    //values
    public function getRouteName() {
        return $this->routeNameSpan()->getText();
    }
    
    public function getRoutePageTitle() {
        return $this->routePageTitle()->getText();
    }
    
    public function getParkingAddressesSize() {
        return count($this->parkingAddressDivs());
    }
    
    public function getHolidaysSize() {
        return count($this->holidayListItems());
    }
    
    //screenshots
    public function screenshotDiffMainDiv() {
        return Screenshot::takeElementScreenshotAndDiff($this->mainDiv(), "SchedulesPageMainDiv");
    }
    
}
