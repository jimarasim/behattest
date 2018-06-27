<?php

use Behat\Behat\Context\Context;
use Pages\RiderGuide;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class RiderGuideContext implements Context
{
    private $riderGuidePage;
    
        /**
     * @Given the Rider Guide page is navigated to
     */
    public function theRiderGuidePageIsNavigatedTo()
    {
        $this->riderGuidePage = new RiderGuide(CommonContext::$driver);
        
        $this->riderGuidePage->open();
    }

    /**
     * @Then the rider guide page should look the same as last time
     */
    public function theRiderGuidePageShouldLookTheSameAsLastTime()
    {
        $this->riderGuidePage->screenShotDiffContentDiv();
    }
}
