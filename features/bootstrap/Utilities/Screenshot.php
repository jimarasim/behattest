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

        //move to element so it's clearly in the view
        $driver->action()->moveToElement($element)->perform();
        
        //if we scrolled, get the page scroll offsets for calculating image position in crop
        $pageXOffset = $driver->executeScript("return window.pageXOffset;");
        $pageYOffset = $driver->executeScript("return window.pageYOffset;");
        
        //take full page screenshot to crop element screenshot from
        $screenshot = Screenshot::takeScreenshot($driver);
        
        //setup path for element screenshot
        $element_screenshot = getcwd().'/screenshots/master/'.$name.'.png';
        //if one already exists in the "master" directory, save it to "taken"
        if(file_exists($element_screenshot)) {
            $element_screenshot = str_replace("master","taken",$element_screenshot);
        }
        
        //get dimensions and location of element screenshot to crop.
        $element_width = $element->getSize()->getWidth();
        $element_height = $element->getSize()->getHeight();      
        $element_src_x = $element->getLocation()->getX()-$pageXOffset;
        $element_src_y = $element->getLocation()->getY()-$pageYOffset;
        print(" X:".$element_src_x." Y:".$element_src_y." WIDTH:".$element_width." HEIGHT:".$element_height."\n");
        print(" window.innerWidth:".$driver->executeScript("return window.innerWidth;")." window.innerHeight:".$driver->executeScript("return window.innerHeight;")."\n");
        $image_dimensions_screenshot = getimagesize($screenshot);
        print(" screenshot width:".$image_dimensions_screenshot[0]." screenshot height:".$image_dimensions_screenshot[1]."\n");
        print(" device pixel ratio:".$driver->executeScript("return window.devicePixelRatio;"));
        
        // crop element screenshot from whole page screenshot, and save it to destination 
        $src = imagecreatefrompng($screenshot);
        $dest = imagecreatetruecolor($element_width, $element_height);
        imagecopy($dest, $src, 0, 0, $element_src_x, $element_src_y, $element_width, $element_height);        
        imagepng($dest, $element_screenshot);
        
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
