@tripplanner
Feature: Trip Planner Page
The sound transit trip planner page at https://soundtransit.org/tripplanner
    Background:
        Given an open browser
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

    @sorry
    Scenario Outline: address outside of service area
        Given I set the start address to "<start_address>"
        And the end address to "<end_address>"
        When I click the plan trip button
        Then the map should load a route
        And I see a We're Sorry message
        Examples:
        |start_address  |end_address        |
        |Bellingham, WA |Seattle, WA        |
        |Seattle, WA    |Bellingham, WA     |
        |Vancouver, WA  |Bellingham, WA     |

    @future
    Scenario: valid route, future date
        Given I set the start address to "98126, WA"
        And the end address to "98121, WA"
        And the date to one week in the future
        When I click the plan trip button
        Then the map should load a route
        And a route is displayed
        
    @arriveby
    Scenario: valid route, arrive by
        Given I set the start address to "98126, WA"
        And the end address to "98121, WA"
        And specify Arrive By instead of Leave At
        When I click the plan trip button
        Then the map should load a route
        And a route is displayed

    @nobus
    Scenario: valid route, no bus
        Given I set the start address to "98126, WA"
        And the end address to "98121, WA"
        And uncheck the bus option
        When I click the plan trip button
        Then I see a We're Sorry message

    @visualregression @vr
    Scenario: valid route, screenshot route
        Given I set the start address to "98126, WA"
        And the end address to "98121, WA"
        When I click the plan trip button
        Then the map should load a route
        And the map element screenshot named "TripPlannerPageMapOf98126To98121" should be visually regressed after its given time to completely render
 
    @visualregression @vr @panel
    Scenario: planner panel visual regression
        Then the planner panel should look the same as last time