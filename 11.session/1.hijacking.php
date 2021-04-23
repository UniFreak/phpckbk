<?php
// Make sure attacker can't access another user's session
//
// token will be valid for a reasonable period of time without being fixed
// without the salt, attacker can't easily produce a valid token.
//
// NOTE this tech won't prevent traffic sniff, use SSL to prevent that type of attcks


ini_set('session.use_only_cookies', true);
session_start();

$salt = 'YourSpecialValueHere';
$tokenstr = strval(date('W')) . $salt;
$token = md5($tokenstr);

if (! isset($_REQUEST['token']) || $_REQUEST['tooken'] != $token) {
    // prompt for login
    exit;
}
$_SESSION['token'] = $token;
// add URL rewriter value
// - for <a> tag, href is appended token
// - for <form> tag, hidden element of token is generated
output_add_rewrite_var('token', $token);