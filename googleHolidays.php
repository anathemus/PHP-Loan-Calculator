<?php
require_once __DIR__.'/vendor/autoload.php';

set_exception_handler('exception_handler');

function start_google_client() {
    session_start();

    $client = new Google_Client();
    $client->setAuthConfig(__DIR__.'/client_secret_953871450148-1fnnuor3qiecnuaorbkd8ljiu9kqnnlb.apps.googleusercontent.com.json');
    $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
    
    try {
        if($client->isAccessTokenExpired()) {
            $client->refreshToken('refresh-token');
        }

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
        } else {
            $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }
    } catch(Exception $e) {
        echo $e;
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }

    return $client;
}

// function to catch uncaught exceptions
function exception_handler($e)
{
    echo $e->getMessage();
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
    if (!(getallheaders())) {
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
}

// function to grab json object from Google Calendar Service
// by using the begin date and end date as a range
function getHolidayArray($date, $endDate) {

    $client = start_google_client();

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
            $holiday = array();
            foreach ($holidayJson['items'] as $items => $property) {
                $DTstart = new DateTime($property['start']['date']);
                $startDate = $DTstart->format('Y-m-d');
                $holiday[$startDate] = array('text' => $property['summary'], 'link' => '#');
            }
            return $holiday;
          }

?>