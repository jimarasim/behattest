<?php 
namespace Pages;

use Facebook\WebDriver\WebDriverBy;

class Home extends Page {
    
    public function url() {return 'https://www.soundtransit.org/';}
   
    //elements
    private function endAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('edit-to'));}    
    private function homePageButton() {return $this->driver->findElement(WebDriverBy::cssSelector('i.icon-home'));}
    private function planTripButton() {return $this->driver->findElement(WebDriverBy::id('edit-submit--2'));}
    private function soundTransitLogo() {return $this->driver->findElement(WebDriverBy::id('masthead'));}
    private function startAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('edit-from'));}
    
    //state checks
    public function homePageButtonDisplayed() {
        return $this->homePageButton()->isDisplayed();
    }
    
    public function homePageButtonEnabled() {
        return $this->homePageButton()->isEnabled();
    }
    
    public function soundTransitLogoDisplayed() {
        return $this->soundTransitLogo()->isDisplayed();
    }
    
    public function startAddressTextboxEnabled() {
        return $this->startAddressTextbox()->isEnabled();
    }
    
    public function endAddressTextboxEnabled() {
        return $this->endAddressTextbox()->isEnabled();
    }
    
    public function planTripButtonEnabled() {
        return $this->planTripButton()->isEnabled();
    }
    
    //clicks
    public function clickHomePageButton() {
        $this->homePageButton()->click();
    }
    
    public function clickPlanTripButton() {
        $this->planTripButton()->click();
        
        $this->waitForUrlContains('Trip-Planner');
        
        return new TripPlanner($this->driver);
    }
    
    //input (sending data to elements)
    public function enterStartAddress($address) {
        $this->startAddressTextbox()->sendKeys($address);
    }
    
    public function enterEndAddress($address) {
        $this->endAddressTextbox()->sendKeys($address);
    }
    
}

