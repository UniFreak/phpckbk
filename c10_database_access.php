<?php
print_r(PDO::getAvailableDrivers());

$db = new PDO("sqlite:/tmp/sqlite.db");
$st = $db->prepare("Select * from student where name=:name and age=:name");
