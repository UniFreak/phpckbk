<?php
// Basic Auth
// ===============================================================
// Cons: username and password are sent in the clear on the network, just
// minimally obscured by Base64 encoding

function validate($user, $pass) {
    $users = ['david' => 'basic'];
    if (isset($users[$user]) && $users[$user] === $pass) {
        return true;
    }
    return false;
}

if (! validate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    http_response_code(401);
    header('WWW-Authenticate: Basic realm="My Website"');
    echo "You need to enter a valid basic.";
    exit;
}

echo "Hello Basic<br />";
