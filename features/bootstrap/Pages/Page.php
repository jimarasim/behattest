<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Utilities\Screenshot;

/**
 * Description of Page
 * 
 * This class represents the base page elements, and includes functionality available to all pages on soundtransit.org, including menu interaction
 *
 * @author jameskarasim
 */
abstract class Page {
    protected $driver;
    abstract public function url();
    
    public function __construct($driver) {
        $this->driver = $driver;
    }
    
    //elements
    private function pageTitleHeader() {return $this->driver->findElement(WebDriverBy::cssSelector('h1.title'));}
    private function footerDiv() {return $this->driver->findElement(WebDriverBy::id('footer'));}

    
    //state checks
    public function menuItemWithTextEnabled($text) {
        return $this->driver->findElement(WebDriverBy::xpath('//li[@data-type="menu_item"]/a[contains(text(),"'.$text.'")]'))->isEnabled();
    }
    
    public function xpathElementEnabled($xpath) {
        return $this->driver->findElement(WebDriverBy::xpath($xpath))->isEnabled();
    }
   
    public function textDisplayed($text) {
        $element = $this->driver->findElement(WebDriverBy::xpath('//*[contains(text(),"'.$text.'")]'));
        return $element->isDisplayed();
    }
    
    //waits
    public function waitForPageUrl() {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::urlIs($this->url())
        );
    }
    
    public function waitForUrlContains($string) {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::urlContains($string)
        );
    }
    
    public function waitForDropdownMenu() {
        $dropdownmenu = $this->driver->findElement(WebDriverBy::cssSelector('div.dropdown-menu'));
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::visibilityOf($dropdownmenu)
        );
    }
    
    //clicks  
    public function clickMenuItemWithText($text){
        return $this->driver->findElement(WebDriverBy::xpath('//li[@data-type="menu_item"]/a[contains(text(),"'.$text.'")]'))->click();
    }
    
    public function clickRiderAlertsMenuItem() {
        $this->driver->action()->moveToElement($this->getMenuItemWithTextContaining('Schedules'))->perform();
        $this->waitForDropdownMenu();
        $this->getMenuItemWithHrefContaining('rider-alerts')->click();
        
        $riderAlertsPage = new RiderAlerts($this->driver);
        $riderAlertsPage->waitForPageUrl();
        
        return $riderAlertsPage;
    }
      
    public function open() {
        $this->driver->get($this->url());
    }
    
    public function currentUrl() {
        return $this->driver->getCurrentURL();
    }
        
    public function getMenuItemWithTextContaining($text){
        return $this->driver->findElement(WebDriverBy::xpath('//li[@data-type="menu_item"]/a[contains(text(),"'.$text.'")]'));
    }
    
    public function getMenuItemWithHrefContaining($text){
        return $this->driver->findElement(WebDriverBy::xpath('//li[@data-type="menu_item"]/a[contains(@href,"'.$text.'")]'));
    }
    
    //values (retrieving data from elements)
    public function getPageTitleHeaderText() {
        return $this->pageTitleHeader()->getText();
    }
    
    //screenshots
    public function screenShotDiffFooterDiv() {
        return Screenshot::takeElementScreenshotAndDiff($this->footerDiv(), 'HomePageFooterDiv');
    }
}
