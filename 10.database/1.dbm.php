<?php
// DBM: an early example of a NoSQL system, like GDBM, NDBM, DB2, DB3, CDB
// All these backends store key/val paires
// Complicated data can be stored with serialization
//
// DBM is a step up from plain-text file, but lacks most feature of SQL DB.
// But it can be good choice for heavily accessed read-only data

// availabel dbm handlers
print_r(dba_handlers());

// mode:
//  r: read-only
//  w: read-write
//  c: create, read-write
//  n: same as c, but if exists, n empties it
$dbh = dba_open(__DIR__ . '/fish.db', 'c', 'ndbm') or die(error_get_last());

// retrieve and change value
if (dba_exists('flounder', $dbh)) {
    $flounder_count = dba_fetch('flounder', $dbh);
    $flounder_count++;
    dba_replace('flounder', $flounder_count, $dbh);
    echo "Updated\n";
} else {
    dba_insert('flounder', 1, $dbh);
    echo "Started count\n";
}

// no more tilapia
dba_delete('tilapia', $dbh);

// what fish do we have?
for ($key = dba_firstkey($dbh); $key !== false; $key = dba_nextkey($dbh)) {
    $value = dba_fetch($key, $dbh);
    echo "$key: $value\n";
}

dba_close($dbh);