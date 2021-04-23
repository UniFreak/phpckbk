<?php
// Open
// ===============================================================
// mode     readable?   writable?   file pointer    truncate?   create?
// --------------------------------------------------------------------
// r        Y           N           Begining        N           N
// r+       Y           Y           B               N           N
// w        N           Y           B               Y           Y
// w+       Y           Y           B               Y           Y
// a        N           Y           E               N           Y
// a+       Y           Y           E               N           Y
// x        N           Y           B               N           Y
// x+       Y           Y           B               N           Y
//
// r: no create (only r)
// +: read & write
// w: truncate (only w)
// a: end (only a)
// x: ^r
//
// Even though Unix handle binary file fine without b in the mode
// It's good to use it always
//
// true: search include_path specified in php.ini
$fh = fopen('file.inc', 'r+', true);

// Temp File
// ===============================================================
// 1. tmpfile(): till script ends or fclose()
$tmp = tmpfile();
fputs($tmp, "The current time is " . strftime('%c'));
fclose($tmp);

// 2. tempnam()
// dir: /tmp, if not exist, use the system temp dir; prefix:data-
$temp = tempnam('/tmp', 'data-');
$temp_fh = fopen($temp, 'w') or die().error_get_last();
fputs($temp_fh, "temp file made by tempnam");
fclose($temp_fh);

// Remote File
// ===============================================================
// config: allow_url_fopen=On
$fh = fopen('http://username:password@www.example.com', 'r'); // or `ftp://

// Stdin
// ===============================================================
$fh = fopen('php://stdin', 'r');

// Read Into String
// ===============================================================
// function             part        return?
// ------------------------------------
// file_get_contents()  entire      Y
// readfile()           entire      print
// fpassthru()          remaining   print


// Counting
// ===============================================================
// 1. lines
$lines = 0;
if ($fh = fopen('file.inc', 'r')) {
    while (! feof($fh)) {
        if (fgets($fh)) {
            $lines++;
        }
    }
}
printf("lines count:%s\n", $lines);

// 2. paragraph: error if have long string of blank lines or a file without
//    two consecutive line breaks
$paragraphs = 0;
rewind($fh);
while (! feof($fh)) {
    $s = fgets($fh);
    if (("\n" == $s) || "\r\n" == $s) {
        $paragraphs++;
    }
}
printf("paragraph counts:%s\n", $paragraphs);

// fix: for small file
function split_paragraphs($file, $rs="\r?\n") {
    $text = file_get_contents($file);
    $matches = preg_split(
                    "/(.*?$rs)(?:$rs)+/s",
                    $text,
                    -1,
                    PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    return $matches;
}

// fix: for large file
function split_paragraph_largefile($file, $rs="\r?\n") {
    $unmatched_text = '';
    $paragraphs = [];

    $fh = fopen($file, 'r');
    while (! feof($fh)) {
        $s = fread($fh, 16*1024);
        $text_to_split = $unmatched_text . $s;
        $matches = preg_split(
                        "/(.*?$rs)(?:$rs)+/s",
                        $text_to_split,
                        -1,
                        PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        // if the last chunk doesn't end with two record separators, save it
        // to prepend to the next section that gets read
        $last_match = $matches[count($matches) - 1];
        if (! preg_match("/$rs$rs\$/", $last_match)) {
            $unmatched_text = $last_match;
            array_pop($matches);
        } else {
            $unmatched_text = '';
        }
        $paragraphs = array_merge($paragraphs, $matches);
    }

    // after reading all secitions, if there is a final chunk that doesn't
    // end with the record separator, count it as a paragraph
    if ($unmatched_text) {
        $paragraphs[] = $unmatched_text;
    }
    return $paragraphs;
}

printf("paragraphs small:\n");
print_r(split_paragraphs('file.inc'));
print_r(split_paragraph_largefile('file.inc'));

// 3. records:
$records = 0;
$separator = '--end--';
rewind($fh);
while (! feof($fh)) {
    $s = rtrim(fgets($fh));
    if ($s == $separator) {
        $records++;
    }
}
printf("records counts:%s\n", $records);

// Random Line
// ===============================================================
$line_number = 0;
rewind($fh);
while (! feof($fh)) {
    if ($s = fgets($fh)) {
        $line_number++;
        if (mt_rand(0, $line_number - 1) == 0) {
            $line = $s;
        }
    }
}
printf("random line:%s\n", $line);

// CSV
// ===============================================================
$delim = '|';
rewind($fh);
while (! feof($fh)) {
    $fields = fgetcsv($fh, 1000, $delim);
    print_r($fields);
}

// Config File
// ===============================================================
// 1. ini file
print_r(parse_ini_file('./conf.ini', 1)); // 1: parse sections

// 2. php file: useful for embeding logic
require "conf.php";
printf("%s\n", $time_of_day);

// Seek
// ===============================================================
$fh = fopen('message.txt', 'r+');
$bytes_to_read = filesize('message.txt');
$next_read = $last_write = 0;
while ($next_read < $bytes_to_read) {
    fseek($fh, $next_read);
    $s = fgets($fh);
    $next_read = ftell($fh);

    $s = strtoupper($s);
    echo $s;
    fseek($fh, $last_write);
    if (-1 == fwrite($fh, $s)) die (error_get_last());
    $last_write = ftell($fh);
}
ftruncate($fh, $last_write);

// Flush: force buffered data to be written
// ===============================================================
fwrite($fh, 'There are twelve pumpkins in my house.');
fflush($fh);

// Lock
// ===============================================================
// In general, if you find yourself needing to lock a file, it's best
// to see if there's an alternative way to solve your problem.
//
// 1. flock() provide *advisory file locking*
// Cons: actually desn't prevent other process from opening a locked file.
//       It block by default until can obtain a lock.
$fh = fopen('file.inc', 'a');
// LOCK_EX: for write
// LOCK_SH: for read
// LOCK_NB: non block
// LOCK_UN: unlock
flock($fh, LOCK_EX);
fwrite($fh, "written while locked\n");
fflush($fh);
flock($fh, LOCK_UN);
fclose($fh);

// 2. Best: using directory
//    Pro: compared to file, there is no gap between checking for existence
//    and creation, so the process that makes the directory is ensured exclusive access
$locked = 0;
while (! $locked) {
    // Works becuase mkdir fails if dir already exists
    if (@mkdir('file.inc.lock', 0777)) {
        $locked = 1;
    } else {
        sleep(1);
    }
}
$fh = fopen('file.inc', 'a');
if (-1 == fwrite($fh, "written while dir lock\n")) {
    rmdir('file.inc.lock');
    die(error_get_last());
}
if (! fclose($fh)) {
    rmdir('file.inc.lock');
    die(error_get_last());
}
rmdir('file.inc.lock');

// 3. using file
$locked = 0;
while (! $locked) {
    if (! file_exists('file.inc.lock')) {
        touch('file.inc.lock');
        $locked = 1;
    } else {
        sleep(1);
    }
}

// Process IO
// ===============================================================
// Passing Input
$ph = popen('/usr/bin/indexer --category=dinner', 'w');
if (-1 == fputs($ph, "red-cooked chicken\n"));
pclose($ph);

// Reading Output
// 1. popen & fgets: line by line, for lot of output
$ph = popen('ls', 'r');
while (! feof($ph)) {
    echo fgets($ph);
}
pclose($ph);

// 2. ``: return all
echo `ls`;


