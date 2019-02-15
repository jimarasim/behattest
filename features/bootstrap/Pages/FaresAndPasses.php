<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;
use Utilities\Screenshot;

/**
 * Description of RiderAlerts
 *
 * @author jameskarasim
 */
class FaresAndPasses extends Page {
    public function url() {return 'https://legacy.soundtransit.org/Fares-and-Passes';}
    
    //elements
    public function contentDiv() {return $this->driver->findElement(WebDriverBy::id('content'));}
    
    //screenshots
    public function screenShotDiffContentDiv() {
        return Screenshot::takeElementScreenshotAndDiff($this->contentDiv(), 'FaresAndPassesContent');
    }
}
