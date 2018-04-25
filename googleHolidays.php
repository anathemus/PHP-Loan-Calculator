<?php
// function to grab json object from Google Calendar Service
// by using the begin date and end date as a range
function getHolidayArray($client, $beginDate, $endDate) {
  $calendarService = new Google_Service_Calendar($client);
            $holidayCalendarId = "en.usa#holiday@group.v.calendar.google.com";
            $events = $calendarService->events;
            $optParam = array("orderBy"=>"startTime", 
                                 "singleEvents"=>true, 
                                 "timeMin"=>($beginDate->format('DATE_RFC3339')) , 
                                 "timeMax"=>($endDate->format('DATE_RFC3339'))); 
            $holidayJson = $events->listEvents($holidayCalendarId);
    
            /*foreach ($holidayJson['items'] as $items => $property) {
                echo $property['summary'];
            }
            */
          }
?>