<?php
$file = __DIR__ . 'lots-of-data.gz';
$fh = fopen("compress.zlib://$file", 'r');
if ($fh) {
    while ($line = fgets($fh)) {

    }
    fclose($fh);
}
