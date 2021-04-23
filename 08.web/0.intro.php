<?php
// Cookie
// ===============================================================

// 1. set cookie
// args: name, val, expire, path, domain, ssl-only
// - if not passing expire, cookie expire when browser is closed
// - if domain is `example.com` instead of `.example.com`
//   sub-domain like `www.example.com` will not be set
setcookie('flavor', 'chocolate', 1417608000);

// 2. read cookie: $_COOKIE

// 3. delete cookie
setcookie('flavor', '', 1); // with no value, and expire in past

// Post
// ===============================================================
// 1. $_POST: designed fro submitted HTML form variable

// 2. $HTTP_RAW_POST_DATA: always_populate_raw_post_data must set to on

// 3. php://input: better than 2, available even always_... set to off

// Writing header
// ===============================================================
header('');

// response code
http_response_code(401); // better exit() in these cases

// redirect: header('Location: http://www.example.com/new_url.php');
// You can't redirect via POST, but can simulate by generating a form that gets
// submitted (via POST) automatically (with JS)

// Flush
// ===============================================================
// flush() force send all output that PHP has internally buffered to the
// web server, but the web server may have internal buffering of its own that
// delay when the data reaches the browser.
//
// ob_flush() should be called before flush() to flush the output buffers if they are in use
//
// some browser don't display data immediately upon receiving it, and some IE
// version don't display until received at least 256 bytes.
//
// To force IE display
echo str_repeat(' ', 300);
echo "Finding identical snowflakes...\n";
flush();

// Compressing Output
// ===============================================================
// zlib.output_compression=1
// ; 1: minimal compression, 9: max
// zlib.output_compression_level=1

// Env
// ===============================================================
// 1. getenv() & putenv()
putenv('MINE=MyEnv');
printf("%s\n", getenv('MINE'));

// 2. $_ENV: PHP auto loads environment varialbes into $_ENV by default
// However, php.ini-production disable this because of speed consideration
// Enable it my adding E to the `variables_order` config
print_r($_ENV);

// 3. apache httpd.conf SetEnv
// Show in $_SERVER, not via getenv() or $_ENV
// Pro: set more restrictive read permissons on env variable than on your PHP scripts.
//      By storing passwords in httpd.conf to avoid placing password in a public available
//      file

// Apache Note:
// communiate from PHP to other parts of Apache request process
// includes setting varialbes in the `access_log`
// ===============================================================
// apache_note('session', 'SESS');
// printf("%s\n", apache_note('session'));

// Browser Detection
// ===============================================================

// 1. get_browser()
// Before use, must config a browser capability file:
// download file at http://browscap.org/
// then config `browsap=php_browsap.ini`
print_r(get_browser());

// 2. $_SERVER['HTTP_USER_AGENT']
printf("%s\n", $_SERVER['HTTP_USER_AGENT']);
