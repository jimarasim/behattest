<?php 
namespace Pages;

use Facebook\WebDriver\WebDriverBy;

class Home extends Page {
    
    public function url() {return 'https://www.soundtransit.org/';}
   
    public function endAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('edit-to'));}    
    public function homePageButton() {return $this->driver->findElement(WebDriverBy::cssSelector('i.icon-home'));}
    public function planTripButton() {return $this->driver->findElement(WebDriverBy::id('edit-submit--2'));}
    public function soundTransitLogo() {return $this->driver->findElement(WebDriverBy::id('masthead'));}
    public function startAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('edit-from'));}

    public function clickPlanTripButton() {
        $this->planTripButton()->click();
        
        $this->waitForUrlContains('Trip-Planner');
        
        return new TripPlanner($this->driver);
    }
    
}

