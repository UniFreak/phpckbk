<?php
$fmt = new MessageFormatter('zh_CN', "it it {0, time, short} on {0, date, medium}");
print $fmt->format([1376943432]);

print_r(localtime());
