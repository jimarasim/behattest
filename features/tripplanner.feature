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
        Then the map should load a route
        And a route is displayed
        Examples:
        |start_address                      |end_address                |
        |1530 3rd Ave, Seattle              |10850 NE 6th St, Bellevue  |
        
    @smoke @geoseattle
    Scenario: Add seattle geocoordinate via map
        Given I context click the map on seattle
        When I click Start Trip Here
        Then a geocoordinate should appear in the start address textbox

    @geobellevue
    Scenario: Add bellevue geocoordinate via map
        Given I context click the map on bellevue
        When I click Start Trip Here
        Then a geocoordinate should appear in the start address textbox

    @feature @georoute
    Scenario: Obtain route through map geocoordinates
        Given I context click the map on seattle
        And I click Start Trip Here
        When I context click the map on bellevue
        And click End Trip Here
        Then the map should load a route 
        And a route is displayed

    @empty
    Scenario: empty start and end address
        Given I havent entered a start address
        And I havent entered an end address
        When I click the plan trip button
        Then an alert pops up saying:
            """
            Trip planner requires both start and end locations.
            """
        And alert can be dismissed by clicking ok