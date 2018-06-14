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

    @feature @hometrip
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
    
    @smoke
    Scenario: Navigate to Alerts page
        Given the Schedules Menu is enabled
        When I click the Alerts menu item
        Then the rider-alerts page is displayed

    @menu
    Scenario Outline: Top-level Menu Items Navigate correctly
        Given top-level menu item "<menu_item>" is enabled
        When I click the "<menu_item>"
        Then the "<page_url>" should be navigated to
        And the page title should be "<page_title>"
        And the "<unique_element_xpath>" should exist

        Examples:
            | menu_item     | page_url                                      | page_title    | unique_element_xpath                                          | 
            | Schedules     | https://www.soundtransit.org/schedule         | Schedules     | //select[@id='edit-route-page-id']                            |
            | Fares & Passes| https://www.soundtransit.org/Fares-and-Passes | Fares & Passes| //img[@alt='Image showing stylized ORCA card in hand']        |
            | Rider Guide   | https://www.soundtransit.org/Rider-Guide      | Rider Guide   | //a[contains(text(),'Airport service')]                       |
            | Trip Planner  | https://www.soundtransit.org/tripplanner      | Trip Planner  | //input[@id='from']                                           |
            | Maps          | https://www.soundtransit.org/Maps             | Maps          | //div[@id='tripplanner-wrap']                                 |
            | Contact Us    | https://www.soundtransit.org/contact-us       | Contact us    | //img[@alt='Exterior photo of the Union Station building.']   |
            | The Platform  | https://www.soundtransit.org/blog/platform    | The Platform  | //a[contains(text(),'About The Platform')]                    |