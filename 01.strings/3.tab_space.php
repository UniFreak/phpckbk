<?php
// doesn't respect tab stop
$tabed = str_replace(' ', "\t", "space string tabed");
$spaced = str_replace("\t", ' ', "  tabed   string  spaced");
printf("tabed:%s\nspaced:%s\n", $tabed, $spaced);

// tab to space, respect tab stop
function tab_expand($text) {
    while (strstr($text, "\t")) {
        $text = preg_replace_callback('/^([^\t\n]*)(\t+)/m', 'tab_expand_helper', $text);
    }
    return $text;
}

function tab_expand_helper($matches) {
    $tab_stop = 8;
    return $matches[1]
        . str_repeat(' ', strlen($matches[2]) * $tab_stop - (strlen($matches[1]) % $tab_stop));
}

$tabed = " tabed\ttext\t\tspaced\t";
$spaced = tab_expand(" tabed   text    to  spaced");
printf("tabed:%s\ntab expand:%s\n", $tabed, $spaced);