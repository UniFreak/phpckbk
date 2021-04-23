<?php
// exchange value without temp variable
// use this technique for clarity, not for speed
// it's not faster than temp variable
$a = 'a'; $b = 'b';
[$a, $b] = [$b, $a];
printf("a: %s, b: %s\n", $a, $b);

// Convert to string
// ===============================================================
// 1. serializatioin: restricted to PHP
$arr = ['a', 'b'];
$ser = serialize($arr);
printf("serialized: %s\n", $ser);
print_r(unserialize($ser));

// 2. json: interact with other language
$json = json_encode($arr);
printf("jsoned: %s\n", $json);
print_r(json_decode($json, true));

// 3. for url
$url = urlencode(serialize($arr));
printf("urlencoded: %s\n", $url);
printf("%s\n", urldecode($url));

// Dump
// ===============================================================
/**
 *                   recursion       capture
 *     --------------------------------------
 *     var_dump()    *RECURSION*     ob_*()
 *     print_r()     *RECURSION*     arg true
 *     var_export()  NULL            arg ture
 */