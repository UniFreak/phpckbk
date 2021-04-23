<?php
// only pass reference when necessary, not as a performance-enhancing trick

// Set default for parameter
// ===============================================================
function image($img) {
    if (! isset($img['src'])) { $img['src'] = 'cow.png'; }
    if (! isset($img['alt'])) { $img['alt'] = 'milk factory'; }
}

function image1($img) {
    $defaults = ['src' => 'cow.png', 'alt' => 'milk factory'];
    $img = array_merge($defaults, $img);
}

// Variable Number of Arg
// ===============================================================
function sum() {
    $sum = 0;
    $n = func_num_args();
    for ($i = 0; $i < $n; $i++) {
        $sum += func_get_arg($i);
    }
    return $sum;
}
printf("sum: %d\n", sum(1, 2, 3));

// Return Value by Reference
// ===============================================================
function &find($needle, &$heystack) {
    foreach ($heystack as $key => $val) {
        if ($needle == $val) {
            return $heystack[$key];
        }
    }
}
$arr = ['a', 'b'];
$a =& find('a', $arr); // use =&
$a = 'a ref'; // can modify $arr with $a
print_r($arr);

// but you can choose not to assign reference and just take the value
$b = find('b', $arr); // not =&, just plain =
$b = 'b ref?'; // can't modify $arr with $b
print_r($arr);

// Return Multiple Value
// ===============================================================
// 1. this is a little efficient, but others are worse becuase is confusing
function time_parts($time) {
    return explode(':', $time);
}
list($hour, $minute, $second) = time_parts('12:34:56');

// 2. con: must know the function prototype for proper usage
function time_parts2($time, &$hour, &$minute, &$second) {
    list($hour, $minute, $second) = explode(':', $time);
}

// 3. con: using global variables
function time_parts3($time) {
    global $hour, $minute, $second;
    list($hour, $minute, $second) = explode(':', $time);
}

// Skip Selected Return Values
// ===============================================================
// can be confusing
list(, $minute, ) = time_parts('12:34:56');
printf("minute: %d\n", $minute);

// Closure
// ===============================================================
$increment = 7;
$add = function($i, $j) use ($increment) { return $i + $j + $increment; };
printf("closure: %d\n", $add(1, 2));
