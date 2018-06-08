Feature: Soundtransit Homepage
The sound transit homepage when navigating to https://soundtransit.org
    Background:
        Given an open chrome browser
        And https://soundtransit.org is navigated to

    @smoke
    Scenario: Home Page Button
        Given the home page button is visible
        And the home page button is enabled
        When I click the home page button
        Then the url should be https://www.soundtransit.org
        And the sound transit logo should appear
        And "Plan your trip!" should be displayed

    @feature
    Scenario Outline: Plan a Trip Happy Path
        Given the Start Destination text field is enabled
        And the End Destination text field is enabled
        And the Plan Trip button is enabled
        When I specify "<start_location>" as the Start Destination
        And "<end_location>" as the End Destination
        And click the Plan Trip button
        Then the url should end with /Trip-Planner
        And a map should be displayed
        And the route should finish loading
        
        Examples:
            | start_location     | end_location|
            | space needle       | safeco field         |
            | Seattle Center, WA | CenturyLink Field, WA|
            | Seattle Center, WA | capitol hill         |          
    
    @alert
    Scenario: Navigate to Alerts page
        Given the Schedules Menu is enabled
        When I move the mouse over the Schedules Menu
        And the menu popup appears
        And I select the Alerts menu item
        Then the rider-alerts page is displayed