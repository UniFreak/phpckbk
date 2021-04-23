<?php
// Using Cookie
// ===============================================================

function validate($user, $pass) {
    $users = ['david' => 'cookie'];
    if (isset($users[$user]) && $users[$user] === $pass) {
        return true;
    }
    return false;
}

unset($username);
$secret_word = 'if i ate apinach';

if (isset($_COOKIE['login'])) { // Validate login
    list($c_username, $cookie_hash) = explode(',', $_COOKIE['login']);
    if (md5($c_username.$secret_word) == $cookie_hash) {
        $username = $c_username;
    } else {
        echo "You have send a bad cookie.";
    }
} elseif (isset($_POST['username'])) { // Logging in
    if (validate($_POST['username'], $_POST['password'])) {
        setcookie('login', $_POST['username'].','.md5($_POST['username'].$secret_word));
        $username = $_POST['username'];
    }
}

if (isset($username)) {
    echo "Welcome: $username.";
} else {
?>

<form method="POST">
Username: <input type="text" name="username"> <br />
Password: <input type="password" name="password"> <br />
<input type="submit" value="Login In">
</form>

<?php
}

// Using Session
// ===============================================================
// store info inside $_SESSION['login']
//
// Session are hijackable
// - use `session.entropy_file` and `session.entropy_length` to make session ID hard to guess
// - use SSL


// Log out
// ===============================================================
// Just delete their login cookie or remove the login variable from their session