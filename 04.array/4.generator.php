<?php
// See: <https://blog.ircmaxell.com/2012/07/what-generators-can-do-for-you.html>
//
// Pros
// - Takes a complex task (storing state explicitly), and lets us do it implicitly
// - Less memory usage

// Basic
function getLines($file) {
    $f = fopen($file, 'r');
    if (!$f) throw new Exception();
    while ($line = fgets($f)) {
        yield $line;
    }
    fclose($f);
}

foreach (getLines('./4.generator.php') as $line) {
    echo "$line\n";
}

// Fibonacci
function fibonacci() {
    $last = 0;
    $current = 1;
    yield 1;
    while (true) {
        $current = $last + $current;
        $last = $current - $last;
        yield $current;
    }
}

foreach (fibonacci() as $n) {
    if ($n > 1000) {
        break;
    }
    echo "$n ";
}

// Sending data back
function createLog($file) {
    $f = fopen($file, 'a');
    while (true) {
        $line = yield; // $line is set with ->send()
        fwrite($f, $line);
    }
}
$log = createLog('./log.txt');
$log->send("First");
$log->send("Second");
$log->send("Third");

// Coroutine: work together, yielding control back and forth
//
// Reading a binary stream which has embedded field length information.
// We could manually link them together, or we could create a series of
// generators to do it for us:
function fetchBytesFromFile($file) {
    $length = yield;
    $f = fopen($file, 'r');
    while (!feof($f)) {
        $length = yield fread($f, $length);
    }
    yield false;
}
function processBytesInBatch(Generator $byteGenerator) {
    $buffer = '';
    $bytesNeeded = 1000;
    while ($buffer .= $byteGenerator->send($bytesNeeded)) {
        // determine if buffer has enough data to be executed
        list($lengthOfRecord) = unpack('N', $buffer);
        if (strlen($buffer) < $lengthOfRecord) {
            $bytesNeeded = $lengthOfRecord - strlen($buffer);
            continue;
        }
        yield substr($buffer, 1, $lengthOfRecord);
        $buffer = substr($buffer, 0, $lengthOfRecord + 1);
        $bytesNeeded = 1000 - strlen($buffer);
    }
}
$gen = processBytesInBatch(fetchBytesFromFile($file));
foreach ($gen as $record) {
    echo "$record\n";
}

// Simulate Threads
//
// you define each “thread” as a generator.
// Then, you “yield” back execution context to the parent,
// so it can pass it to another child (this is basically how “green threads” work).
// So we can build a system that simultaneously processes data from multiple sources
// (as long as we use non-blocking I/O).
function step1() {
    $f = fopen("file.txt", 'r');
    while ($line = fgets($f)) {
        processLine($line);
        yield true;
    }
}
function step2() {
    $f = fopen("file2.txt", 'r');
    while ($line = fgets($f)) {
        processLine($line);
        yield true;
    }
}
function step3() {
    $f = fsockopen("www.example.com", 80);
    stream_set_blocking($f, false);
    $headers = "GET / HTTP/1.1\r\n";
    $headers .= "Host: www.example.com\r\n";
    $headers .= "Connection: Close\r\n\r\n";
    fwrite($f, $headers);
    $body = '';
    while (!feof($f)) {
        $body .= fread($f, 8192);
        yield true;
    }
    processBody($body);
}
function runner(array $steps) {
    while (true) {
        foreach ($steps as $key => $step) {
             $step->next();
             if (!$step->valid()) {
                 unset($steps[$key]);
             }
        }
        if (empty($steps)) return;
    }
}
runner(array(step1(), step2(), step3()));
