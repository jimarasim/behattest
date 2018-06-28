@riderguide
Feature: Rider Guide page
The sound transit rider guide page at https://www.soundtransit.org/Rider-Guide
    Background:
        Given an open browser
        And the Rider Guide page is navigated to
    
    @visualregression @vr
    Scenario: Rider Guide Page Visual Regression
        Then the rider guide page should look the same as last time
    