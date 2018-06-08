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
    
    public function waitForMapToFinishLoading() {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loading'))
        );
    }
}
