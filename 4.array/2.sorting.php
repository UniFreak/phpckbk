<?php
/**
 * Sort Single Array
 * ===============================================================
 *
 *     | will reindex -> keep k/v links -> by key ->  can mix string & numbers (natural)
 * ------------------------------------------------------------------------------------
 * ASC | sort()          asort()            ksort()    natsort(), natcasesort()
 * DSC | rsort()         arsort()           krsort()          -
 * USR | usort()         uasort()           uksort()
 *
 * default is sort lexicographically (1, 10, 2, 20)
 * pass `SORT_NUMERIC` to sort numerically (1, 2, 10, 20)
 */

// usort() requently recomputes comparision result
// to make it faster, cache the comparison value using array_map()
function array_sort($arr, $map_func, $sort_func = '') {
    $mapped = array_map($map_func, $arr); // cache $map_func() values
    if ('' === $sort_func) {
        asort($mapped);                   // asort is fater than usort
    } else {
        uasort($mapped, $sort_func);      // need to preserve keys
    }

    foreach ($mapped as $key => $val) {
        $sorted[] = $arr[$key];
    }
}

// TEST
function u_length($a, $b) {
    $a = strlen($a);
    $b = strlen($b);
    if ($a == $b) return 0;
    if ($a > $b) return 1;
                return -1;
}

function map_length($a) {
    return  strlen($a);
}

$arr = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'];

usort($arr, 'u_length'); // slow: will recompute length
array_sort($arr, 'map_length'); // faster: length cached
print_r($arr);


// Sort Multi-Array At Once
// ===============================================================

$arr = [
    'color' => ['red', 'white', 'blue'],
    'city' => ['boston', 'new york', 'chicage']
];
array_multisort($arr['color'], $arr['city']);
print_r($arr);

