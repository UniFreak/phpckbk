<?php
// Dan Bernstein's time 33 hash: hash string to integer
// 1. start with magic number 5381
// 2. for each byte in string, add the byte and prev hash value times 32

// may overflow due to repeated multiplication
function times_33_hash($str) {
    $h = 5381;
    for ($i = 0, $j = strlen($str); $i < $j; $i++) {
        // <<5 equals *32
        $h += ($h << 5) + ord($str[$i]);
    }
    return $h;
}

// fix
function times_33_hash_fix($str) {
    $h = 5381;
    for ($i = 0, $j = strlen($str); $i < $j; $i++) {
        $h += ($h << 5) + ord($str[$i]);
        // only keep the ower 32 bits
        $h = $h & 0xFFFFFFFF;
    }
    return $h;
}

// test
printf("%d\n", times_33_hash("String"));
printf("%d\n", times_33_hash_fix("String"));