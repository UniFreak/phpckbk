<?php
function str_rand(
    $len = 32,
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $r = '';
    for ($i = $len; $i > 0; $i--) {
        $r .= $chars[mt_rand(0, strlen($chars)-1)];
    }
    return $r;
}

print str_rand(16, '.-');