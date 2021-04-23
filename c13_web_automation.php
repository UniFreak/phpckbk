<?php
$c = curl_init('http://baidu.com/q/en');
print_r(curl_getinfo($c));
print_r(parse_url('http://fanghao:2626@baidu.com/q/en?param=val'));

echo "string"[0];