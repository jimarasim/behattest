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
        //move to element so it's clearly in the view
        $driver->action()->moveToElement($element)->perform();

        //take screenshot of whole page to crop element screenshot out of.
        $screenshot = Screenshot::takeScreenshot($driver);
        
        //path for element screenshot. 
        $element_screenshot = getcwd().'/screenshots/master/'.$name.'.png';
        //if one already exists in the "master" directory, save it to "taken" for diff'ing.
        if(file_exists($element_screenshot)) {
            $element_screenshot = str_replace("master","taken",$element_screenshot);
        }
        
        //get dimensions and location of element screenshot to crop.
        $element_width = $element->getSize()->getWidth();
        $element_height = $element->getSize()->getHeight();      
        $element_src_x = $element->getLocation()->getX();
        $element_src_y = $element->getLocation()->getY();
        
        print('WIDTH:'.$element_width.' HEIGHT:'.$element_height.' X:'.$element_src_x.' Y:'.$element_src_y);
        
        // crop element screenshot from whole page screenshot, and save it to destination 
        $src = imagecreatefrompng($screenshot);
        $dest = imagecreatetruecolor($element_width, $element_height);
        imagecopy($dest, $src, 0, 0, $element_src_x, $element_src_y, $element_width, $element_height);        
        imagepng($dest, $element_screenshot);
     
        // DIFF element screenshot if "master" exists, and this was a "taken" shot
        if(strpos($element_screenshot,"taken")!==FALSE) {
            $master_screenshot = str_replace("taken","master",$element_screenshot);
            
            $master = new Imagick($master_screenshot);
            $taken = new Imagick($element_screenshot);
            
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
        } else {
            print('SAVED MASTER IMAGE FOR FUTURE DIFFING: ' . $element_screenshot);
            return TRUE;
        }
    }
    
}
