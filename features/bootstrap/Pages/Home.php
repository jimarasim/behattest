<?php 
namespace Pages;

use Facebook\WebDriver\WebDriverBy;
use Utilities\Screenshot;

class Home extends Page {
    
    public function url() {return 'https://www.soundtransit.org/';}
   
    //elements
    private function endAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('edit-to'));}    
    private function homePageButton() {return $this->driver->findElement(WebDriverBy::cssSelector('i.icon-home'));}
    private function planTripButton() {return $this->driver->findElement(WebDriverBy::id('edit-submit--2'));}
    private function soundTransitLogo() {return $this->driver->findElement(WebDriverBy::id('masthead'));}
    private function startAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('edit-from'));}
    private function routeOptions() {return $this->driver->findElements(WebDriverBy::xpath("//select[@id='edit-route-name']/optgroup[1]/option"));}
    private function routeOptionByIndex($index) {return $this->driver->findElement(WebDriverBy::xpath("//select[@id='edit-route-name']/optgroup[1]/option[".$index."]"));}
    private function toFromAreaDiv() {return $this->driver->findElement(WebDriverby::id('tofrom-area'));}
    private function blockSystemMainDiv() {return $this->driver->findElement(WebDriverby::id('block-system-main'));}
    private function riderAlertsSubscriptionForm() {return $this->driver->findElement(WebDriverBy::id('rider-alerts-subscription-form'));}

    
    
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
    
    public function clickRandomRoute() {
        $numOptions = count($this->routeOptions());
        $randomOption = rand(1,$numOptions);
        $randomOptionValue = $this->routeOptionByIndex($randomOption)->getAttribute("value");
        
        $this->routeOptionByIndex($randomOption)->click();
        
        $this->waitForUrlContains("schedules");
        
        return $randomOptionValue;
    }
    
    //input (sending data to elements)
    public function enterStartAddress($address) {
        $this->startAddressTextbox()->sendKeys($address);
    }
    
    public function enterEndAddress($address) {
        $this->endAddressTextbox()->sendKeys($address);
    }
    
    //screenshots
    public function screenShotDiffToFromArea() {
        return Screenshot::takeElementScreenshotAndDiff($this->toFromAreaDiv(), 'HomePageToFromArea');
    }
    
    public function screenShotDiffBlockSystemMain() {
        return Screenshot::takeElementScreenshotAndDiff($this->blockSystemMainDiv(), 'HomePageBlockSystemMainDiv');
    }
    
    public function screenShotDiffRiderAlertsSubscription() {
        return Screenshot::takeElementScreenshotAndDiff($this->riderAlertsSubscriptionForm(), 'HomePageRiderAlertsSubscriptionForm');
    }
    
    
}

