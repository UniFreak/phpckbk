<?php
// Digest
// ===============================================================
// Pro: only _hash_ of the password with some other value is sent

$users = ['david' => 'digest'];
$realm = 'My website';
$username = validate_digest($realm, $users);

// never reach this point if invalid auth data is provided
echo "Hello, Digest<br />";

function validate_digest($realm, $users) {
    // fail if no digest has been provided by the client
    if (! isset($_SERVER['PHP_AUTH_DIGEST'])) {
        send_digest($realm);
    }
    // fail if digest can't be parsed
    $username = parse_digest($_SERVER['PHP_AUTH_DIGEST'], $realm, $users);
    if ($username === false) {
        send_digest($realm);
    }
    // valid username was specified
    return $username;
}

function send_digest($realm) {
    http_response_code(401);
    $nonce = md5(uniqid());
    $opaque = md5($realm);
    header("WWW-Authenticate: Digest realm=\"$realm\" qop=\"auth\" "
            . "nonce=\"$nonce\" opaque=\"$opaque\"");
    echo "You need to enter valid digest.";
    // exit;
}

function parse_digest($digest, $realm, $users) {
    // We need to find the following values in the digest header:
    // username, uri, qop, cnonce, nc, response
    $digest_info = [];
    foreach (['username', 'uri', 'nonce', 'cnonce', 'response'] as $part) {
        if (preg_match('/'.$part.'=([\'"]?)(.*?)\1/', $digest, $match)) {
            $digest_info[$part] = $match[2];
        } else {
            return false;
        }
    }
    print_r($digest_info); exit;
    if (preg_match('/qop=auth(,|$)/', $digest)) {
        $digest_info['qop'] = 'auth';
    } else {
        return false;
    }
    if (preg_match('/nc=([0-9a-f]{8}(,|$)/', $digest, $match)) {
        $digest_info['nc'] = $match[1];
    } else {
        return false;
    }

    $A1 = $digest_info['username'] . ':' . $realm . ':'
        . $users[$digest_info['username']];
    $A2 = $_SERVER['REQUEST_METHOD'] . ':' . $digest_info['uri'];
    $request_digest = md5(
                        implode(':', [
                            md5($A1), $digest_info['nonce'],
                            $digest_info['nc'],
                            $digest_info['cnonce'],
                            $digest_info['qop'],
                            md5($A2)]
                        )
                    );
    if ($request_digest != $digest_info['response']) {
        return false;
    }
    return $digest_info['username'];
}