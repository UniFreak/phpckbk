<?php
$lc = ['a', 'b' => 'b'];
$uc = ['A', 'b' => 'B'];
print_r($uc + $lc);
print_r($lc + $uc);

$n;
var_dump(isset($n));

var_dump(in_array(0, ['three']));


$a = range(1, 10000);

$begin = microtime(true);

foreach ($a as $key => $v) {
    if ($v == 5000) {
        echo "found\n";
    }
}

$count1 = microtime(true);
printf("time run %f\n", $count1-$begin);

array_filter($a, function ($item) {
    if ($item == 5000) {
        echo "found\n";
    }
});

$count2 = microtime(true);
printf("time run %f\n", $count2-$count1);

function FileLineGenerator($file) {
    if (!$fh = fopen($file, 'r')) {
        return;
    }
    while (false !== ($line = fgets($fh))) {
        yield $line;
    }
    fclose($fh);
}

$line_number = 0;
foreach (FileLineGenerator('c4_arrays.php') as $line) {
    $line_number++;
    if (mt_rand(0, $line_number -1) == 0) {
        $selected = $line;
    }
}
echo $selected . "\n";