<?php
/**
 * Once you've adopted a technique such as the use of $clean, it's important
 * to only use data from this array in your business logic
 */

// Session Fixation
// ===============================================================
// By session ID regenerated whennever
// - their is a change in privilege level
// - on regular time basis: say every 30 seconds
//
//      session_regenerate_id()
//      session.use_strict_mode

// Spoofing / CSRF
// ===============================================================
// By form token: md5(uniqid(mt_rand(), true))
// store it in session and hidden in form. when form submit, compare form's with sessoion's

// Filter Input
// ===============================================================
$filters = [
    'name' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[z-z]+$/i']],
    'age' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_ragen' => 13]],
];
$clean = filter_input_array(INPUT_POST, $filters);

// XSS
// ===============================================================
// By escape with htmlentities() and always encoding with UTF8
header('Content-Type: text/html; charset=UTF-8');
$html = [];
$html['username'] = htmlentities($clean['username'], ENT_QUOTES, 'UTF-8');
echo "<p> Welcom back, {$html['username']}.</p>";

// SQL Injection
// ===============================================================
// By escaping ($clean) & prepared statement

// Store Sensitive Data
// ===============================================================
// By
// 1. make sure there no pulic viewable page call phpinfo()
// 2. no expose of $_SERVER in other ways
// 3. set variable in separate file from main config file
//          SetEnv DB_USER "susannah"
//          SetEnv DB_PASS "y23a!t@ce8"
// 4. make sure above file is not readable by other user

// Password
// ===============================================================
// By password_hash() & password_verify()

// Detecting SSL
// ===============================================================
if ('on' == $_SERVER['HTTPS']) {
    // true: use SSL only
    setcookie('sslonly', 'yes', 0, '/', 'example.org', true);
}

// MD5
// ===============================================================
// 1. md5()
printf("md5: %s\n", md5('here'));
// 2. bin2hex mhash
printf("md5: %s\n", bin2hex(mhash(MHASH_MD5, 'here')));
// 3. hash(): twice faster than md5()
printf("md5: %s\n", hash('md5', 'here'));