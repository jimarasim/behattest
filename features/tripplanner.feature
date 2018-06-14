@tripplanner
Feature: Trip Planner Page
The sound transit trip planner page at https://soundtransit.org/tripplanner
    Background:
        Given an open chrome browser
        And https://soundtransit.org/tripplanner is navigated to
    
    @feature @triptrip
    Scenario Outline: Plan a trip happy path
        Given I set the start address to "<start_address>"
        And the end address to "<end_address>"
        When I click the plan trip button
        Then a route is displayed
        Examples:
        |start_address                      |end_address                |
        |1530 3rd Ave, Seattle              |10850 NE 6th St, Bellevue  |
        
    @smoke @geoseattle
    Scenario: Add seattle geocoordinate via map
        Given I context click the map on seattle
        When I click Start Trip Here
        Then seattles geocoordinate should appear in the start address textbox

    @geobellevue
    Scenario: Add bellevue geocoordinate via map
        Given I context click the map on bellevue
        When I click Start Trip Here
        Then bellevues geocoordinate should appear in the start address textbox