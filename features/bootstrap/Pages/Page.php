<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * Description of Page
 *
 * @author jameskarasim
 */
abstract class Page {
    protected $driver;
    abstract public function url();
    
    public function pageTitleHeader() {return $this->driver->findElement(WebDriverBy::id('page-title'));}
    
    public function __construct($driver) {
        $this->driver = $driver;
    }
    
    public function open() {
        $this->driver->get($this->url());
    }
    
    public function clickRiderAlertsMenuItem() {
        $this->driver->action()->moveToElement($this->getMenuItemWithTextContaining('Schedules'))->perform();
        $this->waitForDropdownMenu();
        $this->getMenuItemWithHrefContaining('rider-alerts')->click();
        
        $riderAlertsPage = new RiderAlerts($this->driver);
        $riderAlertsPage->waitForPageUrl();
        
        return $riderAlertsPage;
    }
    
    public function getMenuItemWithTextContaining($text){
        return $this->driver->findElement(WebDriverBy::xpath('//li[@data-type="menu_item"]/a[contains(text(),"'.$text.'")]'));
    }
    
    public function getMenuItemWithHrefContaining($text){
        return $this->driver->findElement(WebDriverBy::xpath('//li[@data-type="menu_item"]/a[contains(@href,"'.$text.'")]'));
    }
    
    public function waitForDropdownMenu() {
        $dropdownmenu = $this->driver->findElement(WebDriverBy::cssSelector('div.dropdown-menu'));
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::visibilityOf($dropdownmenu)
        );
    }
    
    public function textDisplayed($text) {
        if($this->driver->findElement(WebDriverBy::xpath('//*[contains(text(),"'.$text.'")]'))->isDisplayed()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

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
}
