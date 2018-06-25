<?php

namespace Utilities;

use Facebook\WebDriver\WebDriverElement;
use CommonContext;
/**
 * Description of Utility
 *
 * @author jameskarasim
 */
class Utility {    
    public static function scrollToElement(WebDriverElement $element) {
        $xpos = $element->getLocation()->getX();
        $ypos = $element->getLocation()->getY();
        
        CommonContext::$driver->executeScript("window.scrollTo(".$xpos.",".$ypos.");");
    }
}
