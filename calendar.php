<?php

function isWeekend($date) {
    $isWeekday = new DateTime($date);
    $weekday= date_format($isWeekday, 'l');
    $normalized_weekday = strtolower($weekday);
    return (($normalized_weekday == "saturday") || ($normalized_weekday == "sunday"));
}

// // sets the beginning date as DateTime object
// function setBeginDate($date)
// {
//   $beginYear = intval(strtotime($date))->format('Y');
//   $beginMonth = intval(strtotime($date))->format('m');
//   $beginDay = intval(strtotime($date))->format('d');
//   $beginDate = new DateTime();
//   $beginDate->date_create_from_format('m-d-Y', $date);

//   return $beginDate;
// }

/**
 * Returns the calendar's html for the given year and month.
 *
 * @param $year (Integer) The year, e.g. 2015.
 * @param $month (Integer) The month, e.g. 7.
 * @param $events (Array) An array of events where the key is the day's date
 * in the format "Y-m-d", the value is an array with 'text' and 'link'.
 * @return (String) The calendar's html.
 */
function build_html_calendar_month($year, $month, $events = null) {

    // CSS classes
    $css_cal = 'calendar';
    $css_cal_row = 'calendar-row';
    $css_cal_day_head = 'bg-light';
    $css_cal_day = 'calendar-day';
    $css_cal_day_number = 'day-number';
    $css_cal_day_blank = 'calendar-day-np';
    $css_cal_day_event = 'calendar-day-event';
    $css_cal_event = 'calendar-event';
  
    // Table headings
    $ltrDays = DateTime::createFromFormat('D', 'Sun');
    $headings = array();
    for ($i=0; $i < 7; $i++) {
      $headings[] = $ltrDays->format('D');
      $ltrDays->add(new DateInterval('P1D'));
    }
    // Start: draw month contents table
    $calendar =
      "<tr class='{$css_cal_row}'>" .
      "<td class='{$css_cal_day_head}'>" .
      implode("</td><td class='{$css_cal_day_head}'>", $headings) .
      "</td>" .
      "</tr>";
  
    // Days and weeks
    $running_day = date('N', mktime(0, 0, 0, $month, 2, $year));
    $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
  
    // Row for week one
    $calendar .= "<tr class='{$css_cal_row}'>";
  
    // Print "blank" days until the first of the current week
    for ($x = 1; $x < $running_day; $x++) {
      $calendar .= "<td class='{$css_cal_day_blank}'> </td>";
    }
  
    // Keep going with days...
    for ($day = 1; $day <= $days_in_month; $day++) {
  
      // Check if there is an event today
      $cur_date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
      $draw_event = false;
      if (isset($events) && isset($events[$cur_date])) {
        $draw_event = true;
      } elseif (isWeekend($cur_date)) {
        $css_cal_day = "text-danger";
      } elseif (!isWeekend($cur_date)) {
        $css_cal_day = "text-dark";
      }
  
      // Day cell
      $calendar .= $draw_event ?
        "<td class='{$css_cal_day} {$css_cal_day_event}'>" :
        "<td class='{$css_cal_day}'>";
  
      // Add the day number
      $calendar .= "<div class='{$css_cal_day_number}'>" . $day . "</div>";
  
      // Insert an event for this day
      if ($draw_event) {
        $calendar .=
          "<div class='{$css_cal_event}'>" .
          "<a href='{$events[$cur_date]['href']}'>" .
          $events[$cur_date]['text'] .
          "</a>" .
          "</div>";
      }
  
      // Close day cell
      $calendar .= "</td>";
  
      // New row
      if ($running_day == 7) {
        $calendar .= "</tr>";
        if (($day + 1) <= $days_in_month) {
          $calendar .= "<tr class='{$css_cal_row}'>";
        }
        $running_day = 1;
      }
  
      // Increment the running day
      else {
        $running_day++;
      }
  
    } // for $day
  
    // Finish the rest of the days in the week
    if ($running_day != 1) {
      for ($x = $running_day; $x <= 7; $x++) {
        $calendar .= "<td class='{$css_cal_day_blank}'> </td>";
      }
    }
  
    // Final row
    $calendar .= "</tr>";
  
    // All done, echo result
    echo $calendar;
  }

  /*
 * @param $year (Integer) The year, e.g. 2015.
 * @param $month (Integer) The month, e.g. 7.
 * @param $events (Array) An array of events where the key is the day's date
 * in the format "Y-m-d", the value is an array with 'text' and 'link'.
 * @return (String) The calendar's html.
 */
  function calendar_month_title($year, $month, $events)
  {
    $dateFromMonth = DateTime::createFromFormat('m', $month);
    $monthNice = date_format($dateFromMonth, 'F');

    echo "<tr>
            <th colspan='7' class='col-10 offset-1 bg-primary text-center'>".$monthNice."</th>
          </tr>";

    build_html_calendar_month($year, $month, $events);

    $lastMonth = $month;
  }

  function create_calendar($date, $endDate, $payPeriod, $events)
  {
    $beginDate = new DateTime($date);
    $previousYear = intval(date_format($beginDate, 'Y'));
    $previousMonth = intval(date_format($beginDate, 'm'));
    $lastYear = intval($endDate->format('Y'));
    $lastMonth = intval($endDate->format('n'));
    $monthArr = array();
    // assign year and month 'check' variables first to make it easier on the computing.
    $year = intval(date_format($beginDate, 'Y'));
    $month = intval(date_format($beginDate, 'm'));
    $monthArr[] = $month;

    echo "<div class='table-responsive'>
    <table class='table table-bordered col-12'>";

    foreach ($payPeriod as $date) {
      $year = intval($date->format('Y'));
      if ($year == $previousYear) {
        $month = intval($date->format('n'));
        if ($month == $previousMonth) {
          // do nothing until the month changes
        } else {
          $monthArr[] = $month;
          $previousMonth = $month;
        }
        // do nothing until the year changes
      } else {
        create_year($year, $monthArr, $events);
        $previousYear = $year;
        unset($monthArr);
        $monthArr = array();
        $monthArr[] = $month;
      }
    
    }

    // if all dates are in the same year, display the year
    if ($year == (intval(date_format($beginDate, 'Y'))) && intval(date_format($endDate, 'Y'))) {
      create_year($year, $monthArr, $events);
    }
      echo "</table>
                </div>";
    

  }

  function create_year($year, $monthArr, $events)
  {
    echo "<tr>
            <th colspan='7' class='col-10 offset-1 bg-success text-center'>".$year."</th>
          </tr>";
          echo count($monthArr);
    foreach ($monthArr as $month) {
      calendar_month_title($year, $month, $events);
    }

  }

  function create_pay_period($date, $endDate)
  {
    $payPeriod = new DatePeriod(
      new DateTime($date),
      new DateInterval('P1D'),
      $endDate
    );

    return $payPeriod;
  }

  function adjust_end_date($payPeriod, $beginDate, $endDate, $client)
  {
    // Check for daily payments then weekends, adjust date of last payment accordingly.
    if ($frequency == 365) {

      foreach($payPeriod as $date){
          if (isWeekend($date)) {
              $endDate->add(new DateInterval('P1D'));
          }
      }

      $hArray = getHolidayArray($beginDate, $endDate);

      foreach ($hArray as $holiday) {
        if (!isWeekend(strtotime($holiday)))
        {
          $endDate->add(new DateInterval('P1D'));
        }
      }
    }
    
    return $endDate;
  }
?>