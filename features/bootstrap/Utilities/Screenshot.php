<?php
namespace Utilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverElement;
use Imagick; //must install ImageMagick and the PHP imagick extension (see notes)

class Screenshot {
    
    public static function takeScreenshot(RemoteWebDriver $driver) {
        $screenshotpath = getcwd().'/screenshots/'.date('YmdHis').'.png';
        
        $driver->takeScreenshot($screenshotpath);
        
        return $screenshotpath;
    }
    
    public static function takeElementScreenshotAndDiff(RemoteWebDriver $driver, WebDriverElement $element, string $name) {
        $screenshot = Screenshot::takeScreenshot($driver);
        
        //if master element screenshot does not exist, create it; otherwise, save a separate copy in taken for diff comparison
        $element_screenshot = getcwd().'/screenshots/master/'.$name.'.png';
        if(file_exists($element_screenshot)) {
            $element_screenshot = str_replace("master","taken",$element_screenshot);
        }
        
        //create cropping of element screenshot by copying main screenshot
        $element_width = $element->getSize()->getWidth();
        $element_height = $element->getSize()->getHeight();
        
        $element_src_x = $element->getLocation()->getX();
        $element_src_y = $element->getLocation()->getY();
        
        // Create image instances
        $src = imagecreatefrompng($screenshot);
        $dest = imagecreatetruecolor($element_width, $element_height);

        // Copy
        imagecopy($dest, $src, 0, 0, $element_src_x, $element_src_y, $element_width, $element_height);        
        imagepng($dest, $element_screenshot);
     
        // DIFF image to master if this was a taken shot
        if(strpos($element_screenshot,"taken")!==FALSE) {
            $master_screenshot = str_replace("taken","master",$element_screenshot);
            
            $master = new Imagick($master_screenshot);
            $taken = new Imagick($element_screenshot);
            
            $result = $master->compareimages($taken, Imagick::METRIC_MEANSQUAREERROR);
            //first array entry is imagick image object. convert it to png and save
            $result[0]->setImageFormat("png");
            $diff_screenshot = getcwd().'/screenshots/diff/'.$name.'.png';
            $result[0]->writeImage($diff_screenshot);
            //second array entry will return a number > 0 if there are differences
            if($result[1]===0) {
                return TRUE;
            } else {
                print('SCREENSHOT DIFF FAILED COMPARISON: ' . $diff_screenshot);
                return FALSE;
            }
        } else {
            print('SAVED MASTER IMAGE FOR FUTURE DIFFING: ' . $element_screenshot);
            return TRUE;
        }
    }
    
}
