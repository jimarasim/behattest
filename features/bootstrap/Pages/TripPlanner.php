<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;

/**
 * Description of TripPlanner
 *
 * @author jameskarasim
 */
class TripPlanner extends Page {
    public function url() {return 'https://www.soundtransit.org/tripplanner';}
    
    public function mapSvg() {return $this->driver->findElement(WebDriverBy::id('map'));}
}
