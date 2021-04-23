<?php
/**
 *          simple            query             auth
 * -------------------------------------------------------------------
 * Stream   file_get_contents http_build_query  user:pass@domain
 *
 * cURL     curl_init/setopt                    CURLOPT_USERPWD
 *               exec/close
 *
 *          redirects                               response
 * -------------------------------------------------------------------
 * Stream   default: auto
 *          manual: stream_context
 *                      stream_context_create(
 *                          [http:[max_redirects]]
 *                      )
 *
 * cURL     *_FOLLOWLOCATION                        1. return: *_RETURNTRANSFER
 *          *_MAXREDIRS                             2. file: *_FILE
 *                                                  3. callback: *_WRITEFUNCTION
 */

// GET
// ===============================================================

// 1. file_get_contents()
//      allow_url_fopen
$url = 'http://example.com/search.php?';
$vars = ['page' => 4, 'search' => 'this & that'];
$qs = http_build_query($vars);
$page = file_get_contents($url . $qs);

// 2. curl
$c = curl_init($url . $qs);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true); // return response

$fh = fopen('local.html', 'w');
curl_setopt($c, CURLOPT_FILE, $fh); // save response to file

curl_setopt($c, CURLOPT_WRITEFUNCTION, 'write_db'); // pass response to function

curl_setopt($c, CURLOPT_FOLLOWLOCATION, true); // redirect
curl_setopt($c, CURLOPT_USERPWD, 'david:hax0r'); // auth
$page = curl_exec($c);

// 3. stream
$options = ['max_redirects' => 1]; // redirect
$context = stream_context_create(['http' => $options]);
$page = file_get_contents($url, false, $context);


// POST or Other Method
// ===============================================================
// 1. stream
$body = 'monkey=uncle&rhino=aunt';
$options = [
    'method' => 'POST', // or put, patch...
    'content' => $body,
    'header' => 'Content-Type: application/x-www-form-urlencoded'];
$context = stream_context_create(['http' => $options]);
file_get_contents($url, false, $context);

// 2. curl
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);

// File Upload
// ===============================================================
curl_setopt($ch, CURLOPT_PUT, true);
curl_setopt($ch, CURLOPT_INFILE, $fp);
curl_setopt($ch, CURLOPT_INFILESIZE, filesize($filename));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);

// Cookie
// ===============================================================
// curl opts
curl_setopt($ch, CURLOPT_COOKIE, 'user=ellen; activity=swimming');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);

// curl cookie jar
//  receive
$jar = tempname('/tmp', 'cookie');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $jar);
//  send
curl_setopt($ch, CURLOPT_COOKIEFILE, $jar);

// Header
// ===============================================================
// 1. stream
$header = 'X-Factor: 12\r\nMy-Header: Bob';
$options = ['header' => $header];
$context = stream_context_create(['http' => $options]);

// 2. curl
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Factor: 12', 'My-Header: Bob']);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.example.com/form.php');
curl_setopt($ch, CURLOPT_USERAGENT, 'cURL via PHP');

// Timeout
// ===============================================================
// 1. file_get_contents()
ini_set('default_socket_timeout', 15); // connect
file_get_contents($url);

// 2. stream
$stream = fopen($url, 'r');
stream_set_timeout($stream, 20); // read
stream_get_contents($stream);

// 3. curl
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); // connect
curl_setopt($ch, CURLOPT_TIMEOUT, 35); // read

// Debugging
// ===============================================================
// 1. stream
$meta = stream_get_meta_data($stream);
forach ($meta['wrapper_data'] as $header) {
    echo $header . "\n";
}
// 2. curl
curl_setopt($ch, CURLOPT_WRITEHEADER, $fh); // write response header to file
curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'debug'); // send response header to callback
curl_setopt($ch, CURLOPT_STDERR $fh); // write std error to file
curl_setopt($ch, CURLOPT_VERBOSE, true); // verbose