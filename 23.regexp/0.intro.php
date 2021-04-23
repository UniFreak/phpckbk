<?php
/**
 * PHP offer two diff set regexp funcitons
 * - traditional (POSIX), ereg_*(), deprecated
 * - Perl-compatible: preg_*(), preferred
 */
$todo = "
first=Get Dressed
next=Eat Jelly
";
// stop at first match, return boolean
print_r(preg_match('/\w+/', $str));


// match all, return
// - number of matches, 0 if zero match
// - false if error
//
// modifier
//   i: case-insentive
//   u: unicode
//   U: ungreedy greedy reverse. default is greedy, incampatible with Perl
//   e: eval, deprecated
//
// $matches store:
//   default: divide into full matches and then submatches
//      0: array of matches of the complete pattern
//      1~*: array of text matched by each parenthesized subpattern
//   PREG_SET_ORDER: divided by matches, with each submatche inside
//
//   subpattern can be made optional using `:?` in subpattern like `(?:prev|next)
$pattern = '/([a-zA-Z]+)=(.*)/u';
print_r(preg_match_all($pattern, $todo, $matches)); // return match count
print_r($matches);

print_r(preg_match_all($pattern, $todo, $matches, PREG_SET_ORDER));
print_r($matches);

// replace
print_r(preg_replace('/([a-zA-Z]+)=/', "($1)=", $todo));

// Finding All Line in File
// ===============================================================
// 1. file() & preg_grep: less time
$pattern = "/\bo'reilly\b/i";
print_r(preg_grep($pattern, file("./file.txt")));

// 2. fopen fgets & preg_match: less memory
$fh = fopen('./file.txt', 'r');
while (! feof($fh)) {
    $line = fgets($fh);
    if (preg_match($pattern, $line)) {
        echo $line;
    }
}
fclose($fh);

// escape PCRE metachars
// ===============================================================
// important if you incorporate user input into a regular expression
// will escape: . \ + * ? ^ $ [ ] ( ) < > = ! | :
// often useful to pass your pattern delimiter as additional so it also gets escaped
$_GET['search_term'] = '(first)';
$search_term = preg_quote($_GET['search_term'], '/');
if (preg_match("/\b$search_term/i", $todo)) {
    echo "match\n";
}

// splie
// ===============================================================
print_r(preg_split('/([a-zA-Z]+)=/', $todo)); // NOTE first element is ''

// callback
// ===============================================================
$h = 'The &lt;b&gt; tag makes text bold; <code>&lt;b&gt;bold&lt;/b&gt;</code>';
printf("%s\n", preg_replace_callback('@<code>(.*?)</code>@', 'decode', $h));

function decode($matches) {
    return html_entity_decode($matches[1]);
}