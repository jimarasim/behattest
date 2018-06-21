@schedules
Feature: Route Schedule page
The sound transit schedule page at https://soundtransit.org/schedules
    Background:
        Given an open browser
        And a route schedule page is navigated to
    
    Scenario: View route parking options
        When I click the Parking tab for the route
        Then I am taken to the route parking section
        And I am shown available parking options for that route

    Scenario: View route holiday schedule
        When I click the Holidays tab for the route
        Then I am taken to the route holidays section
        And am shown upcoming holidays for the route

    Scenario: View route map
        When I click the Map tab for the route
        Then I am taken to the route map section
        And am shown a map for the route