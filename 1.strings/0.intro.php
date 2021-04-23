<?php
// Checkout
// ===============================================================
// - Look and Say: bit.ly/lg2X0sD

// 数字被当成 ASCII 码对应的字母输出
echo "The source cost \$\061\060.\x32\x35"; // $10.25

// HEREDOC, 注意尾部标签末尾必须是换行
// especially useful for printing HTML with interpolated variables
echo <<< END
It's funny
    when signs
    "free" gift
or

END;

// 当连接字符串的时候, 不需要分号
echo <<< END
It's funny
END
. " joined\n";

// NOWDOC, 不能解析变量
// best when you have a block of non-PHP code, ie JS code
$js = <<< '__JS__'
$.ajax({
    'url': '/api/getStock',
    'data': {}
});
__JS__;

echo $js."\n";


// existence: strpos()
strpos("Hello", '@') === false;

// extract substring
// - by specifing pos and length
printf("%s\n", substr("me@domain.com", 5, 10));
// - by search substring
printf("%s\n", strstr("me@domain.com", "@"));

// replace: substr_replace()
echo substr_replace("toooo looong", " ...", 10);

// split
print_r(explode(' ', 'Hello World'));
print_r(preg_split('/\d\. /', '1. get up 2. get dressed'));

// wrap
printf("%s\n", wordwrap("very loooooooooooog content", 10));