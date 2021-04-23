<?php
function checkbirthdate($month, $day, $year) {
    $min_age = 18;
    $max_age = 122;

    if (! checkdate($month, $day, $year)) {
        return false;
    }

    $now = new DateTime();
    $then_formatted = sprintf("%d-%d-%d", $year, $month, $day);
    $then = DateTime::createFromFormat("Y-n-j|", $then_formatted);
    $age = $now->diff($then);
    if ($age->y < $min_age || $age->y > $max_age) {
        return false;
    }
    return true;
}