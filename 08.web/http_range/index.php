<?php
// HTTP Range featuer allows client request one or more section of
// file. Freuently used to dodwnload the remaining portion of file that
// was inperruputed

// Add your auth here. optionally

$file = __DIR__ . '/numbers.txt';
$content_type = 'text/plain';

if (($filelength = filesize($file)) === false) {
    error_log("Problem reading filesize of $file.");
}

if (isset($_SERVER['HTTP_RANGE'])) {
    if (! preg_match('/bytes=\d*-\d*(,\d*-\d*)*$/i', $_SERVER['HTTP_RANGE'])) {
        error_log("Client reqeusted invalid Range.");
        send_error($filelength);
        exit;
    }

    /**
     * Spec: "When a client requests multiple byte-ranges in one request, the
     * server should return them in the order that they appeared in the request."
     */
    // every thing after `bytes=`
    $ranges = explode(',', substr($_SERVER['HTTP_RANGE'], 6));
    $offsets = [];
    foreach ($ranges as $range) {
        $offset = parse_offset($range, $filelength);
        if ($offset !== false) {
            $offsets[] = $offset;
        }
    }

    /**
     * Depending on the number of valid ranges requested, you must return the
     * response in a different format
     */
    switch (count($offsets)) {
        case 0: // No valid ranges
            error_log(("Client requested no valid ranges."));
            send_error($filelength);
            exit;
            break;
        case 1: // One valid range, send standard reply
            http_response_code(206); // Partial Content
            list($start, $end) = $offset[0];
            header("Content-Range: bytes $start-$end/$filelength");
            header("Content-Type: $content_type");

            $content_length = $end - $start + 1;
            $boundaries = [0 => '', 1 => ''];
            break;
        default: // Multiple valid ranges
            http_response_code(206); // Partial Content
            $boundary = str_rand(32);// String to separate each part

            $boundaries = [];
            $content_length = 0;
            foreach ($offsets as $offset) {
                list($start, $end) = $offset;
                $boundary_header =
                    "\r\n"
                    . "--$boundary\r\n"
                    . "Content-Type: $content_type\r\n"
                    . "Content-Range: bytes $start-$end/$filelength\r\n"
                    . "\r\n";
                $content_length += strlen($boundary_header) + ($end-$start + 1);
                $boundaries[] = $boundary_header;
            }

            // Add closing boundary
            $boundary_header = "\r\n--$boundary--";
            $content_length += strlen($boundary_header);
            $boundaries[] = $boundary_header;

            // Chop off extra \r\n
            $boundaries[0] = substr($boundaries[0], 2);
            $content_length -= 2;

            // Change to special multipart Content-Type
            $content_type = "multipart/byterange; boundary=$boundary";
    }
} else { // Send entire file
    $start = 0;
    $end = $filelength - 1;
    $offset = [$start, $end];
    $offsets = [$offset];
    $content_length = $filelength;
    $boundaries = [0=>'', 1 => ''];
}

header("Content-Type: $content_type");
header("Content-Length: $content_length");

$handle = fopen($file, 'r');
if ($handle) {
    $offsets_count = count($offsets);
    for ($i = 0; $i < $offsets_count; $i++) {
        echo $boundaries[$i];
        list($start, $end) = $offset[$i];
        send_range($handle, $start, $end);
    }
    echo $boundaries[$i];
    fclose($handle);
}

function send_range($handle, $start, $end) {
    $line_length = 4096; // magic number
    if (fseek($handle, $start) === -1) {
        error_log("Error: fseek() fail.");
    }
    $left_to_read = $end - $start + 1;
    do {
        $length = min($line_length, $left_to_read);
        if (($buffer = fread($handle, $length)) !== false) {
            echo $buffer;
        } else {
            error_log("Error: fread() fail.");
        }
    } while ($left_to_read -= $length);
}

function send_error($filelength) {
    http_response_code(416); // Range Not Satisfiable
    header("Content-Range: bytes */$filelength"); // required in 416
}

function parse_offset($range, $filelength) {
    /**
     * Spec: "The first-byte-pos value in a byte-range-spec gives the byte-offset
     * of the first byte in a rage. The last-byte-pos value gives the byte-offset
     * of the last byte in the range; that is, the byte position specified are inclusive."
     */
    list($start, $end) = explode('-', $range);

    /**
     * Spec: "A suffix-byte-range-spec is used to specify the suffix of the entity-body,
     * of a length given by the suffix-length value."
     */
    if ($start === '') {
        if ($end === '' || $end === 0) {
            return false;
        } else {
            /**
             * Spec: "If the entity is shorter than the specified suffix-length,
             * the entire entity-body is used. Byte offsets start at zero."
             */
            $start = max(0, $filelength-$end);
            $end = $filelength - 1;
        }
    } else {
        /**
         * Spec: "If the last-byte-pos value is absent, or if the value is greater
         * than or equal to the current length of the entity-body, last-byte-pos is
         * taken to be equal to one less than the current length of the entity-body
         * in bytes"
         */
        if ($end === '' || $end > $filelength - 1) {
            $end = $filelength - 1;
        }

        /**
         * Spec: "If the last-byte-pos value is present, it MUST be greater than or
         * equal to the first-byte-pos in that byte-range-spec, or the byte-range-spec
         * is syntactically invalid."
         * This also catches cases when start > filelength
         */
        if ($start > $end) {
            return false;
        }
    }
    return [$start, $end];
}

function str_rand($length = 32, $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    if (! is_int($length) || $length < 0) {
        return false;
    }
    $chars_length = strlen($chars) - 1;
    $string = '';
    for ($i = $length; $i > 0; $i--) {
        $string .= $chars[mt_rand(0, $chars_length)];
    }
    return $string;
}