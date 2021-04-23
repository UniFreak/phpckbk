<?php
// don't be tempted to bypass fgetcsv() and just read line and explode

// to file
$sales = [['North', '2005', '2006', 12.54], ['South', '2006', '2007', 546.33]];
$file = './sales.csv';
$fh = fopen($file, 'w') or die("Can't open $file");
foreach ($sales as $line) {
    if (fputcsv($fh, $line) === false) {
        die("Can't write");
    }
}
fclose($fh) or die("Can't close $file");

// to stdout
//      make downloadable
header('Content-Type: application/csv');
header('Content-Disposition: attchment; filename="sales.csv"');
$fh = fopen('php://output', 'w');
foreach ($sales as $line) {
    if (fputcsv($fh, $line) === false) {
        die("Can't write to output");
    }
}
fclose($fh);

// to variable
ob_start();
$fh = fopen('php://output', 'w');
foreach ($sales as $line) {
    fputcsv($fh, $line);
}
fclose($fh);
$output = ob_get_contents();
ob_end_clean();
print_r($output);

// parse
$fp = fopen('./sales.csv', 'r');
while ($line = fgetcsv($fp)) {
    print_r($line);
}