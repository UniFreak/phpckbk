<?php
// PDO Connection
// ===============================================================
// mysql
$mysql = new PDO('mysql:host=db.example.com;port=31075;dbnamme=fodd', $user, $pass); // online
$mysql = new PDO('mysql:unix_socket=/tmp/mysql.sock', $user, $pass); // local

// PostgreSQL
$pgsql = new PDO('pgsql:host=db.example.com;port=31075;dbname=food', $user, $pass);
$pgsql = new PDO('pgsql:host=db.example.com port=31075 dbname=food user=$user password=$pass');

// Oracle
$oci = new PDO('oci:dbname=food', $user, $pass); // db name defined in tnsnames.ora
$oci = new PDO('oci:dbname=//db.example.com:1521/food', $user, $pass);

// Sybase
$sybase = new PDO('sybase:host=db.example.com;dbname=food', $user, $pass);

// Microsoft
$mssql = new PDO('mssql:host=db.example.com;dbname=fodd', $user, $pass);

// DBlib
$dblib = new PDO('dblib:host=db.example.com;dbname=food', $user, $pass);

// ODBC
$odbc = new PDO('odbc:food'); // a predefinfed connection
$odbc = new PDO('odbc:Driver={Microsoft Access Driver (*.mdb)};DBQ=C:\\data\\food.mdb;Uid=Chef');

// SQLite
$sqlite = new PDO('sqlite:/usr/local/zodiac.db'); // local file
$sqlite = new PDO('sqlite:memory'); // in-mem temp db
$sqlite = new PDO('sqlite2:/usr/local/old-zodiac.db'); // sqlite2