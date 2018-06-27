@faresandpasses
Feature: Fares and Passes page
The sound transit fares and passes page at https://www.soundtransit.org/Fares-and-Passes
    Background:
        Given an open browser
        And the Fares and Passes page is navigated to
    
    @visualregression @fares
    Scenario: Fares and Passes Page Visual Regression
        Then the fares and passes page should look the same as last time
    