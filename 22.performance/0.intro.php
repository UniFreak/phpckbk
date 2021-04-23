<?php
phpinfo(); exit();
/**
 * Usually, the slow parts of PHP have to do with external resources.
 * network latency and hardware also play a big role.
 *
 * Also avoid regexp if necessary
 *
 * Try to banlance programmer time, ease of debugging and performance
 * while tuning PHP performance
 */

// Accelerator: Opache
// ===============================================================
//  opcahe.memory_consumption=128
//        .interned_strings_buffer=8
//        .max_accelerated_files=4000
//        .revalidate_freq=60
//        .fast_shutdown=1
//        .enable_cli=1
//
// You need to experiment

// Timing
// ===============================================================
// 1. with microtime()
// hash('md5') is twice faster than md5()
// But not in PHP7
$start = microtime(true);
md5('here');
$md5_end = microtime(true);
printf("md5 using: %f\n", $md5_end-$start);

hash('md5', 'here');
$end = microtime(true);
printf("hash using: %f\n", $end - $md5_end);

// 2. by function, with xdebug: easyway to detailed view of code section
//      xdebug.trace_format=0       ; human readable plain text
//            .collect_params=4     ; full variable contents and variable name
//                                  ; in PHP7, use .collect_assignments
//            .collect_return=1     ; show return values
xdebug_start_trace('./trace');
function factorial($x) {
    return ($x == 1) ? 1 : $x * factorial($x-1);
}
echo factorial(10) . "\n";
xdebug_stop_trace();

// 3. tick:
function profile($display = false) {
    static $times;
    switch ($display) {
        case false: // record tick time
            $times[] = microtime();
            break;
        case true: // compute elasped time for every tick
            $start = array_shift($times);
            $start_mt = explode(',', $start);
            $start_total = doubleval($start_mt[0]) + $start_mt[1];
            foreach ($times as $stop) {
                $stop_mt = explode(',', $stop);
                $stop_total = doubleval($stop_mt[0]) + $stop_mt[1];
                $elapsed[] = $stop_total - $start_total;
            }
            unset($times);
            return $elapsed;
            break;
    }
}

register_tick_function('profile');

// clock the start time
profile();

// execute code, recording time for every statement execution
declare (ticks = 1) {
    foreach ($_SERVER['argv'] as $arg) {
        echo "$arg: " . strlen($arg) . "\n";
    }
}

// print out elapsed times
print "---\n";
$i = 0;
foreach (profile(true) as $time) {
    $i++;
    echo "Line $i: $time\n";
}

// 4. Pear Benchmark
require_once 'Benchmark/Timer.php';
$timer = new Benchmark_Timer(true);
$timer->start();
$timer->setMarker('setup');
$timer->setMarker('middle');
$timer->setMarker('done');
$timer->stop();
$timer->display();

// Profiling: Cachegrind - Q/K/WebCachegrind
// ===============================================================
// 1. Xdebug config:
//      xdebug.profiler_enabled = 1
//            .profiler_output_dir
//            .profiler_output_name
//            .profiler_enable_trigger
// 2. run code and
// 3. open grinder and view

// Stress Testing: Siege, ab
// ===============================================================
