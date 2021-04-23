<?php
/**
 * $arr[] is fater than array_push()
 * use array_push() to convery stack nature
 */

$arr[1] = 'Washington'; // key is 1, force key begin with not 0
$arr[] = 'Adams';       // key is 1+1=2
$arr['Honest'] = 'Lincoln'; // key is 'Honest'
$arr[] = 'Jefferson';   // key is 2+1=3
print_r($arr);

print_r(range(1, 52, 2)); // from 1 to 52, step 2

// Iterate
// ===============================================================

// 1. foreach(): over a copy of the array instead of actual array
foreach ($arr as $key => $val) {

}

// 2. each(): over the original array
// if you modify the array inside the loop, you may get expected behavior
//
// NOTE: deprecated in PHP7 because it exposed too much of the internal
// implementation details, blocking language development
reset($arr);
while (list($key, $val) = @each($arr)) {

}

// 3. for(): only for array with consecutive integer keys
// it's inefficient to recompute the count() of $arr
// always use a $size var to hold array size
for ($i = 0, $size = count($arr); $i < $size; $i++) {

}
// if you prefer to count efficiently with one variable, count backward
for ($i = count($arr) - 1; $i >= 0; $i--) {

}

// 4. for(): for associative array
// NOTE: fail if any element holds a string that evaluate to `false`
// so rarely used
for (reset($arr); $key = key($arr); next($arr)) {

}

// 5. array_map(): hand off to funcitons
array_map('strtolower', $arr);

// 6. Generators: for large or expensive dataset

// Delete Element
// ===============================================================
// 1. unset(): doesn't compact to fill the missing hole
unset($arr[1]);

// 2. array_splice(): auto reindex to avoid holes
// useful if you're using the array as a queue and to remove item from queue
// while still allowing random access
array_splice($arr, 2, 2); // from index 2, length 2
print_r($arr);

// Grow
// ===============================================================

// pad(): add new
//
// to 5 element, with 'New' added to right
// if -5, then to left
// if already have 5 element, then no 'New' added
print_r(array_pad($arr, 5, 'New'));

// array_merge(): merge
// - with only numerical keys, keys are renumbered, no value lost
// - with assoc keys, second overwrite first
print_r(array_merge(['zero', 'one' => 'one'], ['ZERO', 'one' => 'ONE']));

// +: aslo merge, VS array_merge()
// - prefer left array
// - doesn't do reordering to prevent collision
print_r(['zero', 'one' => 'one'] + ['ZERO', 'one' => 'ONE']); // you get all from left

// Convert to String
// ===============================================================
// 1. join(): is faster than loop
printf("%s\n", join(',', $arr));

// 2. loop
// add then remove leading ',' is far cleaner and efficient than
// embed logic inside the loop, like
//      if (!empty($str)) { $str .= ',' }
$str = '';
foreach ($arr as $val) {
    $str .= ",$val";
}
$str = substr($str, 1);
printf("%s\n", $str);

// Checking Key Existence
// ===============================================================
// 1. array_key_exists(): true even value is null
printf("%d\n", array_key_exists('one', ['one'=>null]));

// 1. isset(): false if value is null
printf("%d\n", isset(['one'=>null]['one']));

// Checking Value Existence
// ===============================================================
// 1. in_array(): take linear time
printf("%d\n", in_array(0, ['three'])); // NOTE: return true. becuase default behavior use ==
printf("%d\n", in_array(0, ['three'], true)); // pass true to use ===

// 2. array_flip() and isset(): faster, contant time
$arr = ['one', 'two'];
$flipped = array_flip($arr);
printf("%d\n", isset($flipped['one']));

// 3. array_search(): return position
// NOTE: if multiple same value, only guarantee to return one of the instance
// BUT NOT the first instance
if (($pos = array_search('one', ['one', 'two'])) !== false) { // NOTE: use !==
    printf("found one in position %d\n", $pos);
}

// Find Certain Element
// ===============================================================
// 1. foreach(): flexible and easy to understand, faster
// 2. array_filter(): NOTE: impossible to bail out early. prefer foreach()
// 3. max(), min()

// Reverse
// ===============================================================
// array_reverse(): often possible to avoid via
// - use another sort
// - reverse the loop
// - invert the insertion
print_r(array_reverse(['one', 'two']));

// Walk
// ===============================================================
// array_walk(): walk in-place without returning
$arr = ['Bob', 'Joe'];
array_walk($arr, function (&$val, $key) { // so pass reference if need
    $val .= '.';
});
print_r($arr); // Error: Array to string conversion

// array_walk_recursive(): passes all nonarray element (even nested) to callback
$arr = ['Bob', 'Joe', 'other' => ['dog', 'cat']];
array_walk_recursive($arr, function (&$val, $key) {
    $val .= '.';
});
print_r($arr);

// Set Ops
// ===============================================================
// 1. union
$a = ['a', 'b'];
$b = ['b', 'c'];
print_r(array_unique(array_merge($a, $b)));

// 2. intersection
print_r(array_intersect($a, $b));

// 3. difference
print_r(array_diff($a, $b)); // case insensive. no 'c' because it's not in $a

// 4. symmetric difference
print_r(array_merge(array_diff($a, $b), array_diff($b, $a)));

// Array Like Object: SPL's ArrayAccess