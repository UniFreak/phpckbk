<?php
function lookandsay($s) {
    $r = '';
    $m = $s[0];
    $n = 1;
    for ($i = 1, $j = strlen($s); $i < $j; $i++) {
        if ($s[$i] == $m) {
            $n++;
        } else {
            $r .= $n.$m;
            $m = $s[$i];
            $n = 1;
        }
    }
    return $r.$n.$m;
}

for ($i = 0, $s = 1; $i < 10; $i++) {
    $s = lookandsay($s);
    print "$s\n";
}