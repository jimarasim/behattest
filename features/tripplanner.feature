Feature: Trip Planner Page
The sound transit trip planner page at https://soundtransit.org/tripplanner
    Background:
        Given an open chrome browser
        And https://soundtransit.org/tripplanner is navigated to
    @triptrip
    Scenario Outline:
        Given I set the start address to "<start_address>"
        And the end address to "<end_address>"
        When I click the plan trip button
        Then a route is displayed
        Examples:
        |start_address              |end_address        |
        |1 Microsoft Way, Redmond   |3504 sw webster st |