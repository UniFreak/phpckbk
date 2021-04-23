<?php
// with distribution array
function rand_weighted($numbers) {
    $total = 0;
    foreach ($numbers as $number => $weight) {
        $total += $weight;
        $distribution[$number] = $total;
    }
    $rand = mt_rand(0, $total-1);
    foreach ($distribution as $number => $weight) {
        if ($rand < $weight) return $number;
    }
}

// with generator
function incremental_total($numbers) {
    $total = 0;
    foreach ($numbers as $number => $weight) {
        $total += $weight;
        yield $number => $total;
    }
}

function rand_weighted_generator($numbers) {
    $total = array_sum($numbers);
    $rand = mt_rand(0, $total-1);
    foreach (incremental_total($numbers) as $number => $weight) {
        if ($rand < $weight) return $number;
    }
}

// TEST
$ads = ['ford' => 12234, 'att' => 33424, 'ibm' => 16823];
printf("%s, %s\n", rand_weighted($ads), rand_weighted_generator($ads));