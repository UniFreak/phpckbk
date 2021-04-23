<?php
class STD {

}

var_dump(0 == "also");

$a[0] = 0;
$a[1] = null;
var_dump(isset($a[0]));
var_dump(isset($a[1]), array_key_exists(1, $a));

var_dump(function_exists('ftok'));
var_dump(function_exists('shmop_open'));
var_dump(function_exists('sem_get'));

$user1 = ['name' => 'Max'];
$user2 = ['name' => 'Leo'];
$user2['friend'] = &$user1;
$user1['friend'] = &$user2;

print_r($user1);
var_dump($user1);
var_export($user1);