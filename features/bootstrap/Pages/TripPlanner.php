<?php

namespace Pages;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * Description of TripPlanner
 *
 * @author jameskarasim
 */
class TripPlanner extends Page {
    //overrides
    public function url() {return 'https://www.soundtransit.org/tripplanner';}
    
    //elements
    public function mapSvg() {return $this->driver->findElement(WebDriverBy::id('map'));}
    public function startAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('from'));}
    public function endAddressTextbox() {return $this->driver->findElement(WebDriverBy::id('to'));}
    public function planTripButton() {return $this->driver->findElement(WebDriverBy::id('trip-submit'));}
    public function tripResultSummaryTable() {return $this->driver->findElement(WebDriverBy::id('tripresult-summaries'));}

    //map location offsets; offsets from the center of the map element
    public $seattleOffset = array("x"=>-6, "y"=>-43);
    public $bellevueOffset = array("x"=>20, "y"=>-45);
    
    //state checks
    public function tripResultSummaryTableEnabled() {
        return $this->tripResultSummaryTable()->isEnabled();
    }

    public function startAddressTextboxEnabled() {
        return $this->startAddressTextbox()->isEnabled();
    }
    
    public function startAddressMatchesGeocode() {
        $pattern = '/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/';
        $subject = $this->getStartAddressText();
        
        if(preg_match($pattern,$subject)===1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    //waits (waiting for page updates)
    public function waitForMapToStartLoading() {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('loading'))
        );
    }
    
    public function waitForMapToFinishLoading() {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loading'))
        );
    }
    
    public function waitForAlert() {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::alertIsPresent()
        );      
    }
    
    public function waitForWereSorryHeader() {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//div[@id=\"trip-data\"]/div[@id=\"no-results\"]/h3[contains(text(),\"We're sorry!\")]"))
        );
    }
    
    //clicks
    public function clickMapStartTripHereFromContextClick() {
        $this->driver->action()->moveByOffset(60,10)->click()->perform();
    }
    
    public function clickMapEndTripHereFromContextClick() {
        $this->driver->action()->moveByOffset(60,30)->click()->perform();
    }
    
    public function contextClickMapOnSeattle() {
        //build actions to simulate a context click on seattle
        //moveToElement moves to the center of the element
        //using preview, was able to get exact pixels from middle of map to seattle
        $this->driver->action()->moveToElement($this->mapSvg())->moveByOffset($this->seattleOffset["x"],$this->seattleOffset["y"])->contextClick()->perform();
    }
    
    public function contextClickMapOnBellevue() {
        //build actions to simulate a context click on seattle
        //moveToElement moves to the center of the element
        //using preview, was able to get exact pixels from middle of map to seattle
        $this->driver->action()->moveToElement($this->mapSvg())->moveByOffset($this->bellevueOffset["x"],$this->bellevueOffset["y"])->contextClick()->perform();
    }
    
    public function clickPlanTripButton() {
        $this->planTripButton()->click();
    }
    
    public function acceptAlert() {
        $this->driver->switchTo()->alert()->accept();
    }
    
    //input (sending data to elements)
    public function enterStartAddress($address) {
        $this->startAddressTextbox()->sendKeys($address);
    }
    
    public function enterEndAddress($address) {
        $this->endAddressTextbox()->sendKeys($address);
    }
    
    public function clearStartAddress() {
        $this->startAddressTextbox()->clear();
    }
    
    public function clearEndAddress() {
        $this->endAddressTextbox()->clear();
    }
    
    //values (retrieving data from elements)
    public function getStartAddressText() {
        $startAddressText = $this->driver->executeScript("return document.getElementById('from').value;");
        return $startAddressText;
    }
    
    public function getAlertMessage() {
        return $this->driver->switchTo()->alert()->getText();
    }
}
