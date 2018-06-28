<?php
namespace Utilities;

use CommonContext;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverPoint;
use Imagick; //must install ImageMagick and the PHP imagick extension (see notes)
use Utilities\Utility;

class Screenshot {
    
    //full page screenshots are named with a datestamp and saved to the /screenshots folder
    public static function takeScreenshot() {
        $driver = CommonContext::$driver;
        
        $screenshotpath = getcwd().'/screenshots/'.date('YmdHis').'.png';
        
        $driver->takeScreenshot($screenshotpath);
        
        return $screenshotpath;
    }
    
    //element screenshots require a moniker and are saved to /screenshots/master if one hasn't been taken
    //if a master has been taken, they are saved to /screenshots/taken
    public static function takeElementScreenshot(WebDriverElement $element, string $moniker, array $hidden=NULL) {
        $driver = CommonContext::$driver;
 
        //setup path for element screenshot
        $element_screenshot = Screenshot::masterElementScreenshotPath($moniker);
        //if one already exists in the "master" directory, save it to "taken"
        if(file_exists($element_screenshot)) {
            $element_screenshot = Screenshot::takenElementScreenshotPath($moniker);
        }
        
        //get best view of the element
        Screenshot::optimizeWindowForElementScreenshot($element);
        Utility::scrollToElement($element);
        
        //take full page screenshot to crop element screenshot from
        $screenshot = Screenshot::takeScreenshot($driver);
        
        //get the element dimensions so we know what to crop
        $element_dimensions = Screenshot::getElementDimensions($element);
        
        // crop element screenshot from whole page screenshot, and save it to destination 
        $src = imagecreatefrompng($screenshot);
        $dest = imagecreatetruecolor($element_dimensions["width"], $element_dimensions["height"]);
        imagecopy($dest, $src, 0, 0, $element_dimensions["x"], $element_dimensions["y"], $element_dimensions["width"], $element_dimensions["height"]);        
        imagepng($dest, $element_screenshot);
        
        return $element_screenshot;
    }
    
    private static function getElementDimensions(WebDriverElement $element) {
        $driver = CommonContext::$driver;   
        
        $dimension_array = array();

        //if we're scrolled, get the page scroll offsets for calculating image position in crop
        $pageXOffset = $driver->executeScript("return window.pageXOffset;");
        $pageYOffset = $driver->executeScript("return window.pageYOffset;");
        
        //if we're on a high resolution display, pixels returned for any dimension will be n times as much because the screenshot will be n times as large
        $devicePixelRatio = $driver->executeScript("return window.devicePixelRatio;");
        
        //get dimensions and location of element screenshot to crop.
        $dimension_array["width"] = $devicePixelRatio*$element->getSize()->getWidth();
        $dimension_array["height"] = $devicePixelRatio*$element->getSize()->getHeight();      
        $dimension_array["x"] = $devicePixelRatio*$element->getLocation()->getX()-$devicePixelRatio*$pageXOffset;
        $dimension_array["y"] = $devicePixelRatio*$element->getLocation()->getY()-$devicePixelRatio*$pageYOffset;
        
        return $dimension_array;
    }
    
    //master element screenshot will be taken if one does not exist
    public static function takeElementScreenshotAndDiff(WebDriverElement $element, string $moniker) {
        $master_screenshot = Screenshot::masterElementScreenshotPath($moniker);
        
        if(!file_exists($master_screenshot)) {
            Screenshot::takeElementScreenshot($element, $moniker);
            print("WARN: MASTER AND TAKEN IMAGES TAKEN AT SAME TIME. RUN AGAIN FOR TRUE DIFF TEST.\n");
        }
        
        $taken_screenshot = Screenshot::takeElementScreenshot($element, $moniker);
        
        $master = new Imagick($master_screenshot);
        $taken = new Imagick($taken_screenshot);

        $result = $master->compareimages($taken, Imagick::METRIC_MEANSQUAREERROR);
        //first array entry is imagick image object for the DIFF image. convert it to png and save
        $result[0]->setImageFormat("png");
        $diff_screenshot = Screenshot::diffElementScreenshotPath($moniker);
        $result[0]->writeImage($diff_screenshot);
        //second array entry will return 0.0 if the images are exact
        if($result[1]===0.0) {
            return TRUE;
        } else {
            print('SCREENSHOT DIFF FAILED. RESULT:'.$result[1].' DIFF:'.Screenshot::diffElementScreenshotPath($moniker));
            return FALSE;
        }
    }
    
    private static function masterElementScreenshotPath($moniker) {
        return getcwd().'/screenshots/master/'.$moniker.Screenshot::monikerAttributes();
    }
    
    private static function takenElementScreenshotPath($moniker) {
        return getcwd().'/screenshots/taken/'.$moniker.Screenshot::monikerAttributes();
    }
    
    private static function diffElementScreenshotPath($moniker) {
        return getcwd().'/screenshots/diff/'.$moniker.Screenshot::monikerAttributes();
    }
    
    private static function monikerAttributes() {
        //if devicePixelRatio is higher than 1, we'll take a device specific screenshot
        $devicePixelRatio = CommonContext::$driver->executeScript("return window.devicePixelRatio;");
        
        //screenshots should be specific to browser too, since they take screenshots slightly differently
        return '-'.CommonContext::$browser.'-'.$devicePixelRatio.'.png';
    }
    
    private static function optimizeWindowForElementScreenshot(WebDriverElement $element) {
        CommonContext::$driver->manage()->window()->setPosition(new WebDriverPoint(0,0));
        //get element, window, and viewport dimensions and calculate new window size
        $elementWidth = $element->getSize()->getWidth();
        $elementHeight = $element->getSize()->getHeight();
        $viewPortSize = CommonContext::$driver->executeScript("return [window.innerWidth, window.innerHeight];");
        $viewPortWidth = $viewPortSize[0];
        $viewPortHeight = $viewPortSize[1];
        
        if($elementWidth > $viewPortWidth || $elementHeight > $viewPortHeight) {
            $windowSize = CommonContext::$driver->manage()->window()->getSize();
            $windowWidth = $windowSize->getWidth();
            $windowHeight = $windowSize->getHeight();
            $nonViewPortWidth = $windowWidth - $viewPortWidth;
            $nonViewPortHeight = $windowHeight - $viewPortHeight;

            $newWindowWidth = ($elementWidth > $viewPortWidth)?$elementWidth + $nonViewPortWidth:$windowWidth;
            $newWindowHeight = ($elementHeight > $viewPortHeight)?$elementHeight + $nonViewPortHeight:$windowHeight;
            
            //set window size to fit element
            CommonContext::$driver->manage()->window()->setSize(new WebDriverDimension($newWindowWidth,$newWindowHeight));
        }
    }
}
