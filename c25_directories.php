<?php
$dir = new RecursiveDirectoryIterator('.');
$total = 0;
foreach (new RecursiveIteratorIterator($dir) as $file) {
    $total += $file->getSize();
}
print "The total size is $total\n";

printf("%3u", '!');

print urlencode("a/ab/");