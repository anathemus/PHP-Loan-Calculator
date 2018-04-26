<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/header.php';

$client = start_google_client();
// function to grab json object from Google Calendar Service
// by using the begin date and end date as a range
function getHolidayArray($client, $date, $endDate) {

    $beginDate = new DateTime($date);
    $calendarService = new Google_Service_Calendar($client);
            $holidayCalendarId = "en.usa#holiday@group.v.calendar.google.com";
            $events = $calendarService->events;

            $beginGoogleDate = date_format($beginDate, 'Y-m-d\TH:i:s.u\Z');
            $endGoogleDate = date_format($endDate, 'Y-m-d\TH:i:s.u\Z');

            $optParams = array(
                'maxResults' => 2500, 
                'orderBy' => 'startTime',
                'singleEvents' => TRUE,
                'timeMin' => $beginGoogleDate,
                'timeMax' => $endGoogleDate
              ); 
            $holidayJson = $events->listEvents($holidayCalendarId, $optParams);
    
            foreach ($holidayJson['items'] as $items => $property) {
                $holiday = array($property['start.date'] => array('text' => $property['summary'], 'link' => '#'));
            }
            return $holiday;
          }
?>