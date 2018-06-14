<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * Description of TripPlanner
 *
 * @author jameskarasim
 */
class TripPlanner extends Page {
    public function url() {return 'https://www.soundtransit.org/tripplanner';}
    
    public function mapSvg() {return $this->driver->findElement(WebDriverBy::id('map'));}
    public function startAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('from'));}
    public function endAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('to'));}
    public function planTripButton() {return $this->driver->findElement(WebDriverBy::id('trip-submit'));}
    public function tripResultSummaryTable() {return $this->driver->findElement(WebDriverBy::id('tripresult-summaries'));}

    public function tripResultSummaryTableEnabled() {
        return $this->tripResultSummaryTable()->isEnabled();
    }
    
    public function waitForMapToStartLoading() {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('loading'))
        );
    }
    
    public function waitForMapToFinishLoading() {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loading'))
        );
    }
    
    public function enterStartAddress($address) {
        $this->startAddressTextbox()->sendKeys($address);
    }
    
    public function enterEndAddress($address) {
        $this->endAddressTextbox()->sendKeys($address);
    }
    
    public function clickPlanTripButton() {
        $this->planTripButton()->click();
        $this->waitForMapToStartLoading();
        $this->waitForMapToFinishLoading();
    }
}
