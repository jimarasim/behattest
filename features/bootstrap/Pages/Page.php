<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;

/**
 * Description of Page
 *
 * @author jameskarasim
 */
class Page {
    protected $driver;
    
    public function __construct($driver) {
        $this->driver = $driver;
    }
    
    public function textDisplayed($text) {
        if($this->driver->findElement(WebDriverBy::xpath('//*[contains(text(),"'.$text.'")]'))->isDisplayed()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
