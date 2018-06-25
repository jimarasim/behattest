<?php
namespace Utilities;

use CommonContext;
use Facebook\WebDriver\WebDriverElement;
use Imagick; //must install ImageMagick and the PHP imagick extension (see notes)


class Screenshot {
    
    //full page screenshots are named with a datestamp and saved to the /screenshots folder
    public static function takeScreenshot() {
        $driver = CommonContext::$driver;
        
        $screenshotpath = getcwd().'/screenshots/'.date('YmdHis').'.png';
        
        $driver->takeScreenshot($screenshotpath);
        
        return $screenshotpath;
    }
    
    //element screenshots require a name and are saved to /screenshots/master if one hasn't been taken
    //if a master has been taken, they are saved to /screenshots/taken
    public static function takeElementScreenshot(WebDriverElement $element, string $name) {
        $driver = CommonContext::$driver;
 
        //setup path for element screenshot
        $element_screenshot = getcwd().'/screenshots/master/'.$name.'.png';
        //if one already exists in the "master" directory, save it to "taken"
        if(file_exists($element_screenshot)) {
            $element_screenshot = str_replace("master","taken",$element_screenshot);
        }
        
        //move to element so it's clearly in the view
        $driver->action()->moveToElement($element)->perform();
        
        //take full page screenshot to crop element screenshot from
        $screenshot = Screenshot::takeScreenshot($driver);
        
        //if we scrolled, get the page scroll offsets for calculating image position in crop
        $pageXOffset = $driver->executeScript("return window.pageXOffset;");
        $pageYOffset = $driver->executeScript("return window.pageYOffset;");
        
        //if we're on a high resolution display, pixels returned for any dimension will be n times as much
        $devicePixelRatio = $driver->executeScript("return window.devicePixelRatio;");
        
        //get dimensions and location of element screenshot to crop.
        $element_width = $devicePixelRatio*$element->getSize()->getWidth();
        $element_height = $devicePixelRatio*$element->getSize()->getHeight();      
        $element_src_x = $devicePixelRatio*$element->getLocation()->getX()-$devicePixelRatio*$pageXOffset;
        $element_src_y = $devicePixelRatio*$element->getLocation()->getY()-$devicePixelRatio*$pageYOffset;
        
        // crop element screenshot from whole page screenshot, and save it to destination 
        $src = imagecreatefrompng($screenshot);
        $dest = imagecreatetruecolor($element_width, $element_height);
        imagecopy($dest, $src, 0, 0, $element_src_x, $element_src_y, $element_width, $element_height);        
        imagepng($dest, $element_screenshot);
        
        //if the device pixel ratio is greater than 1, then we need to size it down to 1 for consistency across devices
        if($devicePixelRatio > 1) {
            $dest_resized = imagecreatetruecolor($element_width/$devicePixelRatio, $element_height/$devicePixelRatio);
            imagecopyresampled($dest_resized, $dest, 0, 0, 0, 0, $element_width/$devicePixelRatio, $element_height/$devicePixelRatio, $element_width, $element_height);
            imagepng($dest_resized, $element_screenshot);
        }
        
        return $element_screenshot;
    }
    
    //master element screenshot will be taken if one does not exist
    public static function takeElementScreenshotAndDiff(WebDriverElement $element, string $name) {
        $master_screenshot = getcwd().'/screenshots/master/'.$name.'.png';
        
        if(!file_exists($master_screenshot)) {
            Screenshot::takeElementScreenshot($element, $name);
            print("WARN: MASTER AND TAKEN IMAGES TAKEN AT SAME TIME. RUN AGAIN FOR TRUE DIFF TEST.");
        }
        
        $taken_screenshot = Screenshot::takeElementScreenshot($element, $name);
        
        $master = new Imagick($master_screenshot);
        $taken = new Imagick($taken_screenshot);

        $result = $master->compareimages($taken, Imagick::METRIC_MEANSQUAREERROR);
        //first array entry is imagick image object for the DIFF image. convert it to png and save
        $result[0]->setImageFormat("png");
        $diff_screenshot = getcwd().'/screenshots/diff/'.$name.'.png';
        $result[0]->writeImage($diff_screenshot);
        //second array entry will return 0.0 if the images are exact
        if($result[1]===0.0) {
            return TRUE;
        } else {
            print('SCREENSHOT DIFF FAILED COMPARISON: ' . $diff_screenshot);
            return FALSE;
        }
    }
}
