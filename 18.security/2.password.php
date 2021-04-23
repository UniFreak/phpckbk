<?php
// User Logging In
session_start();
try {
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');
    $user = User::findByEmail($email);
    if (password_verify($password, $user->password_hash) === false) {
        throw new Exception('Invalid password');
    }

    // 为什么需要重新计算哈希值
    //  因为如果应用创建与 2 年前, 那时使用的 bcrypt 工作因子是 10
    //  现在计算机速度快了, 可能需要使用 20. 可是有些账户仍然是 10 时
    //  生成的, 因此需要检查是否需要更新
    $currentHashAlgo = PASSWORD_DEFAULT;
    $currentHashOptions = ['cost' => 15];
    $passwordNeedsRehash = password_needs_rehash(
        $user->password_hash,
        $currentHashAlgo,
        $currentHashOptions
    );
    if ($password_needs_rehash === true) {
        $user->password_hash = password_hash(
            $password,
            $currentHashAlgo,
            $currentHashOptions
        );
        $user->save();
    }

    $_SESSION['user_logged_in'] = 'yes';
    $_SESSION['user_email'] = $email;

    header('HTTP/1.1 302 Redirect');
    header('Location: /user-profile.php');
} catch (Exception $e) {
    header('HTTP/1.1 404 Unauthorized');
    echo $e->getMessage();
}