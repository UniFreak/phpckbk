<?php
// Time handle best practice:
// 1. treat time internally as UTC
// 2. treat time as Unix epoch
// 3. set `date.timezone` correctly or call `date_default_timezone_set()` ahead
// 4. use DateTime instead of strftime()... funcs

// Y2K issue:

// Y2038 issue:
// Jan 19, 2038 3:14:07 is 2147483647 (2^32-1), largest 32 bit integer

// get date
printf("%s\n", date('r')); // r: RFC 2822-formatted date
printf("%s\n", (new DateTime())->format('r'));

// get time parts
print_r(getdate());
print_r(localtime());

// Convert
// ===============================================================

// DT parts to epoch

printf("%s\n", mktime(19, 45, 3, 3, 10, 1975)); // H i s m d Y
printf("%s\n", gmmktime(19, 45, 3, 3, 10, 1975)); // GMT-based

$birth = DateTime::createFromFormat("*: F j, Y.|", "Birthday: May 11, 1918.");
printf("%s\n", $birth->format(DateTime::RFC850));

// epoch to DT parts
$when = new DateTime("@163727100"); // @ means is an epoch ts
$when->setTimeZone(new DateTimeZone('America/Los_Angeles'));
$parts = explode('/', $when->format('Y/m/d/H/i/s'));
print_r($parts);

// Format
// ===============================================================
// date and ::format use same code internally
printf("%s\n", date('d/M/Y'));
printf("%s\n", (new DateTime())->format('d/M/Y'));


// Diff
// ===============================================================

$first_local = new DateTime("1965-05-10 7:32:56pm", new DateTimeZone('America/New_York'));
$second_local = new DateTime("1962-11-20 4:29:11am", new DateTimeZone('America/New_York'));

// problem: actual amount is an hour less than output (due to repeating clock-hour
// in the fall switch to standard time)
$diff = $second_local->diff($first_local);
printf("diff 1: %d weeks, %s days, %d hours, %d minutes, %d seconds\n",
    floor($diff->format('%a') / 7),
    $diff->format('%a') % 7,
    $diff->format('%h'),
    $diff->format('%i'),
    $diff->format('%s'));

// fix
$first = new DateTime('@'.$first_local->getTimestamp());
$second = new DateTime('@'.$second_local->getTimestamp());
$diff = $second->diff($first);
printf("diff 1: %d weeks, %s days, %d hours, %d minutes, %d seconds\n",
    floor($diff->format('%a') / 7),
    $diff->format('%a') % 7,
    $diff->format('%h'),
    $diff->format('%i'),
    $diff->format('%s'));

// Parse from string
strtotime('march 10');
strtotime('last thursday');
strtotime('now + 3 months');
