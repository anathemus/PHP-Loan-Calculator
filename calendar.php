<?php

function isWeekend($date) {
    $weekday= $date->format('l');
    $normalized_weekday = strtolower($weekday);

    return (($normalized_weekday == "saturday") || ($normalized_weekday == "sunday"));
}

?>