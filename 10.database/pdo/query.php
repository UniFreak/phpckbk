<?php
// Connect
$db_file = __DIR__ . '/sqlite.db';
$db = new PDO("sqlite:/$db_file");

// Query
$sql = 'select * from zodiac';
$rows = $db->query($sql);

// fetch one, in loop: fetch()
// ===============================================================
$firstRow = $rows->fetch();
print_r($firstRow);

// fetch bound: set up variable whose value refreshed each time
// fetch() is called
$rows = $db->query($sql, PDO::FETCH_BOUND);
$rows->bindColumn('symbol', $symbol); // by col name
$rows->bindColumn(2, $planet); // by col num
while ($rows->fetch()) {
    print("$symbol goes with $planet. \n");
}

// fetch into: each time fetch a row, stuff values into properties of the
// $row variable. Useful if want to keep data aroudn in the same object,
// such as whether you're displaying an odd or even numbered row.
class AvgStatement extends PDOStatement { // extending PDOStatement
    public function avg() {
        $sum = 0;
        $vars = get_object_vars($this);
        // remove PDOStatement built-in 'queryString'
        unset($vars['queryString']);
        foreach ($vars as $var => $value) {
            $sum += strlen($value);
        }
        return $sum / count($vars);
    }
}
$row = new AvgStatement;
$results = $db->query('select symbol, planet from zodiac', PDO::FETCH_INTO, $row);
while ($results->fetch()) {
    echo "$row->symbol belongs to $row->planet (Average: {$row->avg()}) \n";
}

// fetch all, no loop: fetchAll()
// ===============================================================
$st = $db->query($sql);
$results = $st->fetchAll();
foreach ($results as $i => $result) {
    echo "Planet $i is {$result['planet']} \n";
}

// modify data
// ===============================================================
// 1. PDO::exec()
//      $db->exec("insert into ...")
// 2. prepared statement
//      $st = $db->prepare('? ? ?');
//      $st->execute([]);

// find row number
// ===============================================================
// 1. with rowCount(): not reliable, not for selection
//      $st->rowCount();
// 2. with count(): for selection
printf("count:%d\n", count($results));
// 3. better wich COUNT(*) in query

// bind params:
// pros: security and speed
// ===============================================================
// 1. with ?
$st = $db->prepare('select sign from zodiac where element like ?');
$st->execute(['fire']);
while ($row = $st->fetch()) {
    echo "$row[0]\n";
}

// 2. with names
$st = $db->prepare('select sign from zodiac where element like :element');
$rows = $st->execute(['element' => 'earth']);

// 3. with bindParams()
// if use ?, index start at 0
$pairs = ['Mars' => 'water', 'Moon' => 'water', 'Sun' => 'fire'];
$st = $db->prepare("select sign from zodiac where element like :element"
                   . " and planet like :planet");
$st->bindParam(':element', $element);
$st->bindParam(':planet', $planet);
foreach ($pairs as $planet => $element) {
    $st->execute();
    print_r($st->fetch());
}
// for bind in paticular type, pass third argument
// - PDO::PARAM_NULL    null
// - PDO::PARAM_BOOL    boolean
// - PDO::PARAM_INT     int
// - PDO::PARAM_STR     string
// - PDO::PARAM_LOB     "large object"  useful for sutff stream content into db efficiently
$st = $db->prepare('insert into files (path, contents) values (:path, :contents)');
$st->bindParam(':path', $path);
$st->bindParam(':contents', $fp, PDO::PARAM_LOB);
foreach (glob('/usr/local/*') as $path) {
    $fp = fopen($path, 'r');
    $st->execute();
}


// Error Handle
// ===============================================================
// 1. erorrInfo() & errorCode()
$st = $db->prepare('select * from imaginary_tabl');
if (! $st) {
    $error = $db->errorInfo();
    printf("problem: %s\n", $error[2]);
}

// 2. when creating new PDO object, instead an exception is throwed
//    you can force exception by `setAttribute())`
//    It's good idea to set up a default exception handler
try {
    $db = new PDO('...');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {

}