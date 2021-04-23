<?php
/**
 * Stream Filter: must be applied to a stream after it is created.
 *
 * Read/Write Wrapper
 * - compress.zlib
 * - compress.bzip2
 * Compress/Decompress Filter
 * - zlib.deflat://
 * - zlib.inflat://
 * - bzip2.compress://
 * - bzip2.uncompress://
 */
$fp = fopen('http://www.example.org/something-compressed.gz', 'r');
if ($fp) {
    stream_filter_append($fp, 'bzip2.uncompress');
    while (! feof($fp)) {
        $data = fread($fp);
    }
    fclose($fp);
}