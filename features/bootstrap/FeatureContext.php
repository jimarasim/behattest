<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $driver;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        putenv("webdriver.chrome.driver=selenium/chromedriver");
    }

    /**
     * @Given an open chrome browser
     */
    public function anOpenChromeBrowser()
    {
        
        $this->driver = ChromeDriver::start();
    }

    /**
     * @Given https:\/\/soundtransit.org is navigated to
     */
    public function httpsSoundtransitOrgIsNavigatedTo()
    {
        $this->driver->get('https://soundtransit.org');
    }

    /**
     * @Given the home page button is visible
     */
    public function theHomePageButtonIsVisible()
    {
        $element = $this->driver->findElement(WebDriverBy::cssSelector('i.icon-home'));
        
        Assert::assertTrue($element->isDisplayed());  
    }

    /**
     * @Given the home page button is enabled
     */
    public function theHomePageButtonIsEnabled()
    {
        $element = $this->driver->findElement(WebDriverBy::cssSelector('i.icon-home'));
        
        Assert::assertTrue($element->isEnabled());
    }

    /**
     * @When I click the home page button
     */
    public function iClickTheHomePageButton()
    {
        $element = $this->driver->findElement(WebDriverBy::cssSelector('i.icon-home'));
        
        $element->click();
    }

    /**
     * @Then the url should be https:\/\/www.soundtransit.org
     */
    public function theUrlShouldBeHttpsWwwSoundtransitOrg()
    {
        $url = $this->driver->getCurrentURL();
        
        Assert::assertEquals($url,'https://www.soundtransit.org/');  
    }

    /**
     * @Then the sound transit logo should appear
     */
    public function theSoundTransitLogoShouldAppear()
    {
        $logo = $this->driver->findElement(WebDriverBy::id('masthead'));
        
        Assert::assertTrue($logo->isDisplayed());
    }

    /**
     * @Then :arg1 should be displayed
     */
    public function shouldBeDisplayed($arg1)
    {
        $text = $this->driver->findElement(WebDriverBy::xpath('//*[contains(text(),"'.$arg1.'")]'));
        
        Assert::assertTrue($text->isDisplayed());
    }
    
        /**
     * @Given the Start Destination text field is enabled
     */
    public function theStartDestinationTextFieldIsEnabled()
    {
        Assert::assertTrue($this->driver->findElement(WebDriverBy::id('edit-from'))->isEnabled());
    }

    /**
     * @Given the End Destination text field is enabled
     */
    public function theEndDestinationTextFieldIsEnabled()
    {
        Assert::assertTrue($this->driver->findElement(WebDriverBy::id('edit-to'))->isEnabled());
    }

    /**
     * @Given the Plan Trip button is enabled
     */
    public function thePlanTripButtonIsEnabled()
    {
        Assert::assertTrue($this->driver->findElement(WebDriverBy::id('edit-submit--2'))->isEnabled());
    }

    /**
     * @When I specify :arg1 as the Start Destination
     */
    public function iSpecifyAsTheStartDestination($arg1)
    {
        $this->driver->findElement(WebDriverBy::id('edit-from'))->sendKeys($arg1);
    }

    /**
     * @When :arg1 as the End Destination
     */
    public function asTheEndDestination($arg1)
    {
        $this->driver->findElement(WebDriverBy::id('edit-to'))->sendKeys($arg1);
    }

    /**
     * @When click the Plan Trip button
     */
    public function clickThePlanTripButton()
    {
        $this->driver->findElement(WebDriverBy::id('edit-submit--2'))->click();
    }

    /**
     * @Then the url should end with \/Trip-Planner
     */
    public function theUrlShouldEndWithTripPlanner()
    {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::urlContains('/Trip-Planner')
        );
    }

    /**
     * @Then a map should be displayed
     */
    public function aMapShouldBeDisplayed()
    {
        Assert::assertTrue($this->driver->findElement(WebDriverBy::id('map'))->isDisplayed());
    }

    /**
     * @Then the route should finish loading
     */
    public function theRouteShouldFinishLoading()
    {
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loading'))
        );
    }
    
        /**
     * @Given the Schedules Menu is enabled
     */
    public function theSchedulesMenuIsEnabled()
    {
        Assert::assertTrue($this->driver->findElement(WebDriverBy::xpath('//li[@data-type="menu_item"]/a[contains(text(),"Schedules")]'))->isEnabled());
    }

    /**
     * @When I move the mouse over the Schedules Menu
     */
    public function iMoveTheMouseOverTheSchedulesMenu()
    {
        $schedules = $this->driver->findElement(WebDriverBy::xpath('//li[@data-type="menu_item"]/a[contains(text(),"Schedules")]'));
        
        $this->driver->action()->moveToElement($schedules)->perform();
        
    }

    /**
     * @When the menu popup appears
     */
    public function theMenuPopupAppears()
    {
        $dropdownmenu = $this->driver->findElement(WebDriverBy::cssSelector('div.dropdown-menu'));
        $this->driver->wait(10, 500)->until(
            WebDriverExpectedCondition::visibilityOf($dropdownmenu)
        );
    }

    /**
     * @When I select the Alerts menu item
     */
    public function iSelectTheAlertsMenuItem()
    {
        $this->driver->findElement(WebDriverBy::xpath('//li[@data-type="menu_item"]/a[contains(@href,"rider-alerts")]'))->click();
    }

    /**
     * @Then the rider-alerts page is displayed
     */
    public function theRiderAlertsPageIsDisplayed()
    {
        $pagetitle = $this->driver->findElement(WebDriverBy::id('page-title'));
        
        Assert::assertEquals($pagetitle->getText(),'Rider Alerts');
    }
    
    /**
     * @AfterScenario
     */
    public function after()
    {
        if($this->driver) {
            $this->driver->quit();
        }
    }
}
