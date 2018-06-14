A sample PHP project using bhat, php-webdriver, and php-unit to test the soundtransit.org site.

Tests use standalone chromedriver, so that selenium standalone server does not need to be run beforehand.

To run the tests on OSX:
1. Clone this repository and cd into it
2. Install composer 
    https://getcomposer.org/download/
3. Install project dependencies using composer:
    php composer.phar install
4. run bhat
    vendor/bin/behat
    vendor/bin/behat --tags=@smoke

