<?php
require_once 'Benchmark/Timer.php';

$timer = new Benchmark_Timer(true);
$timer->start();
$timer->setMarker('setup');
$timer->setMarker('middle');
$timer->setMarker('done');
$timer->stop();
$timer->display();