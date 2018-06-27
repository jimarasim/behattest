<?php

use Behat\Behat\Context\Context;
use Pages\FaresAndPasses;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FaresAndPassesContext implements Context
{
    private $faresAndPassesPage;
    /**
     * @Given the Fares and Passes page is navigated to
     */
    public function theFaresAndPassesPageIsNavigatedTo()
    {
        $this->faresAndPassesPage = new FaresAndPasses(CommonContext::$driver);
        
        $this->faresAndPassesPage->open();
    }

    /**
     * @Then the fares and passes page should look the same as last time
     */
    public function theFaresAndPassesPageShouldLookTheSameAsLastTime()
    {
        Assert::assertTrue($this->faresAndPassesPage->screenShotDiffContentDiv());
    }
}
