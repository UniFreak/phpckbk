<?php
// By char
printf("%s\n", strrev("Hello world"));

// by words
$words = explode(' ', "Hello world");
$words = array_reverse($words);
$s = implode(' ', $words);
printf("%s\n", $s);