<?php
// 1. Best, for array that already complete
$arr = ['one', 'one', 'two'];
print_r(array_unique($arr));

// 2. for building numerical array
$uniq = [];
foreach ($arr as $key => $val) {
    if (! in_array($val, $uniq)) {
        $uniq[] = $val;
    }
}
print_r($uniq);

// 3. eliminate in_array() linear time check. faster
$uniq = [];
foreach ($arr as $val) {
    $uniq[$val] = $val;
}
$uniq = array_values($uniq);
print_r($uniq);