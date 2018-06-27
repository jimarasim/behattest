@schedules
Feature: Route Schedule page
The sound transit schedule page at https://soundtransit.org/schedules
    Background:
        Given an open browser
    
    @visualregression @vr
    Scenario: Main Schedules Page Visual Regression
        Given the main schedules page is open
        Then the main schedules page should look the same as last time
    
    Scenario: View route parking options
        Given a route schedule page is navigated to
        When I click the Parking tab for the route
        Then I am taken to the route parking section
        And I am shown available parking options for that route

    Scenario: View route holiday schedule
        Given a route schedule page is navigated to
        When I click the Holidays tab for the route
        Then I am taken to the route holidays section
        And am shown upcoming holidays for the route

    Scenario: View route map
        Given a route schedule page is navigated to
        When I click the Map tab for the route
        Then I am taken to the route map section
        And am shown a map for the route
