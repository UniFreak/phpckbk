<?php
$db = new PDO('sqlite:user.db');
$email = 'david';
$verify_string = '';
for ($i = 0; $i < 16; $i++) {
    // COODIE
    $verify_string .= chr(mt_rand(32, 126));
}

$sth = $db->prepare("insert into users"
                    ."(email, created_on, verify_string, verified)"
                    ."valuels (?, datetime('now'), ?, 0)");
$sth->execute([$email, $verify_string]);

$verify_string = urlencode($verify_string);
$safe_email = urlencode($email);
$verify_url = "/verify_user.php";

$mail_body = <<<_MAIL_
To $email;

Please click on the following link to verify your account creation:

$verify_url?email=$safe_email&verify_string=$verify_string

If you don't verify your account in the next seven days, it will be deleted.

_MAIL_;

mail($email, "User Verification", $mail_boy);