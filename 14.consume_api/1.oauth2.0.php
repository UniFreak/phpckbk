<?php
/**
 * OAuth 2.0 dance:
 * 1. redirect user to API provider, passing along self-generated secret value
 *     known as `state`, and the URL where user should return after sign in
 * 2. user sign into that site, which auth user and ask him to auth your app
 *     to make API calls on his behalf
 * 3. API provider redirect user back to your app, passing along two data:
 *     1. the same state you provided to match up each reply with its corresponding user
 *     2. and a code
 * 4. you excahnge the code for a permanent OAuth token for the user, passing along
 *     your app ID and secret to identify yourself
 * 5. you make API calls on behalf of the user
 *
 * Each API provider flow same steps, but need alter keys and URLs
 */
define('API_KEY', '');
define('API_SECRET', '');
define('REDIRECT_URI', 'http://yourapp.com/here.php');
define('SCOPE', 'r_fullprofile r_emailaddress rw_nus');

session_name('linkedin');
session_start();

// OAuth flow
if (isset($_GET['error'])) {
    // linkedin return error
    echo $_GET['error'] . ': ' . $_GET['error_description'];
    exit;
} elseif (isset($_GET['code'])) {
    // User authed your app
    if ($_SESSION['state'] == $_GET['state']) {
        // Get token so you can make API calls
        getAccessToken();
    } else {
        // CSRF attack? or did you mix up your states?
        exit;
    }
} else {
    if ((empty($_SESSION['expires_at'])) || (time() > $_SESSION['expires_at'])) {
        // Token expired, clear the state
        $_SESSION = [];
    }
    if (empty($_SESSION['access_token'])) {
        // Start auth process
        getAuthorizationCode();
    }
}

// Congratulation! You have a valid token, Now fetch profile
$user = fetch('GET', '/v1/people/!:(firstName)');
echo "Hello $user->firstName.\n";
exit;

function getAuthorizationCode() {
    $params = [
        'response_type' => 'code',
        'client_id' => API_KEY,
        'scope' => SCOPE,
        'state' => uniqid('', true), // unique long string
        'redirect_uri' => REDIRECT_URI,
    ];
    $url = 'https://www.linkedin.com/uas/oauth2/authorization?' . http_build_query($params);
    $_SESSION['state'] = $params['state'];
    header("Location: $url");
    exit;
}

function getAccessToken() {
    $params = [
        'grant_type' => 'authorization_code',
        'client_id' => API_KEY,
        'client_secret' => API_SECRET,
        'code' => $_GET['code'],
        'redirect_uri' => REDIRECT_URI,
    ];
    $url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);
    $context = stream_context_create(['http' => ['method' => 'POST']]);
    $response = file_get_contents($url, false, $context);

    $token = json_decode($response);
    $_SESSION['access_token'] = $token->access_token;
    $_SESSION['expires_in'] = $token->expires_in;
    $_SESSION['expires_at'] = time() + $_SESSION['expires_in'];

    return true;
}

function fetch($method, $resource, $body = '') {
    $params = ['oauth2_access_token' => $_SESSION['access_token'], 'format' => 'json'];
    $url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
    $context = stream_context_create(['http' => ['method' => $method]]);
    $response = file_get_contents($url, false, $context);
    return json_decode($response);
}