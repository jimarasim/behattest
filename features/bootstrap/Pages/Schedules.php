<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;

/**
 * Description of Schedules
 *
 * @author jameskarasim
 */
class Schedules extends Page {
    public function url() {return 'https://www.soundtransit.org/schedules/';}
    
    //elements
    private function routeNameSpan() {return $this->driver->findElement(WebDriverBy::cssSelector('span.route-name'));}    
    
    //values
    public function getRouteName() {
        return $this->routeNameSpan()->getText();
    }
    
}
