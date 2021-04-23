<?php
require 'Net/Whois.php';
$server = 'whois.godaddy.com';
$query = 'oreilly.com';
$whois = new Net_Whois();
print_r($whois->query($query, $server));