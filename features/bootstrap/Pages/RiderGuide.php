<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;
use Utilities\Screenshot;

/**
 * Description of RiderGuide
 *
 * @author jameskarasim
 */
class Riderguide extends Page {
    public function url() {return 'https://www.soundtransit.org/Rider-Guide';}
    
    //elements
    public function contentDiv() {return $this->driver->findElement(WebDriverBy::id('content'));}
    
    //screenshots
    public function screenShotDiffContentDiv() {
        return Screenshot::takeElementScreenshotAndDiff($this->contentDiv(), 'RiderGuideContent');
    }
}
