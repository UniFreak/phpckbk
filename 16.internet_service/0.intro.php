<?php
// Reading Mail: Imap or POP3
// ===============================================================

$mail = imap_open('{mail.google.com:143}', 'username', 'password'); // imap
$mail = imap_open('{mail.server.com:110/pop3}', 'username', 'password'); // pop3

$headers = imap_headers($mail);
$last = imap_hnum_msg($mail);
$header = imap_header($mail, $last);
$body = imap_body($mail, $last);
imap_close($mail);

// FTP
// ===============================================================
// 1. cURL
$c = curl_init("ftp://$username:$password@ftp.example.com/$remote");
$fh = fopen($local, 'w');
curl_setopt($c, CURLOPT_FILE, $fh);
curl_exec($c);
curl_close($c);

// 2. ftp_*()
$ftp = ftp_connect('ftp.example.com');
ftp_login($ftp, $username, $password);
ftp_set_option($ftp, FTP_TIMEOUT_SEC, 120);
ftp_put($ftp, $remote, $local, FTP_ASCII); // upload
ftp_get($ftp, $local, $remote, FTP_ASCII); // download
ftp_close($ftp);


// LDAP: DB optimized for storing info about people
// ===============================================================
// Concepts:
// address repo is called data source
// each entry in repo has GUID, known as distinguished name
// cn: common name, o: organization, c: country
// auth to ldap known as binding
$ds = ldap_connect('ldap.example.com');
ldap_bind($ds, $username, $password);
$sr = ldap_search($ds, 'o=Example Inc., c=US', 'sn=*');
$e = ldap_get_entries($ds, $sr);
for ($i = 0; $i < $e['count']; $i++) {
    echo $e[$i]['cn'][0] . ' (' . $e[$i]['mail'][0] . ')' . "\n";
}
ldap_close($ds);

// DNS
// ===============================================================
$ip = gethostbyname('www.yahoo.com');
$ips = gethostbynamel('www.yahoo.com'); // get all hosts ips
$host = gethostbyaddr('93.184.216.119');
getmxrr('yahoo.com', $hosts, $weight);
print_r(dns_get_record('www.yahoo.com', DNS_AAAA));