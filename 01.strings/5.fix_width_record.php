<?php
// pack()
$books = [['Elmer Gantry', 'Sinclair Lewis', 1927], ['Scarlatti', 'Rober', 1971]];
foreach ($books as $book) {
    printf("%s\n", pack('A25A15A4', $book[0], $book[1], $book[2]));
}

// str_pad()
foreach ($books as $book) {
    $title = str_pad(substr($book[0], 0, 25), 25, '.');
    $author = str_pad(substr($book[0], 0, 15), 15, '.');
    $year = str_pad(substr($book[0], 0, 4), 4, '.');
    printf("%s\n", $title.$author.$year);
}

// parsing
// ===============================================================

// substr()
$fp = fopen('records.txt', 'r');
while ($s = fgets($fp, 1024)) {
    $fields[0] = substr($s, 0, 25);
    $fields[1] = substr($s, 25, 15);
    $fields[3] = substr($s, 40, 4);
    $fields = array_map('rtrim', $fields);
    print_r($fields);
}

// unpack()
function record_unpack($format_string, $data) {
    $r = [];
    for ($i = 0, $j = count($data); $i < $j; $i++) {
        $r[$i] = unpack($format_string, $data[$i]);
    }
    return $r;
}
$books = [
    "Elmer Gantry             Sinclair Lewis 1927",
    "Scarlatti                Rober          1971"
];
$books = record_unpack('A25title/A15author/A4year', $books);
print_r($books);