<?php
// Checkout
// ===============================================================
// - PHP float: php.net/language.types.float
// - Time 33 hash: bit.ly/lg8c4F6

printf("is numeirc:%d\n", is_numeric('6.9'));

// 6.99999... is 6 in PHP
// dont use float if this precision matters, use BCMath and GMP
printf("%d\n", 6.9999999);

// never trust floating number results to the last digit, and do not
// compare floating point numbers directly for equality
// instead, use a small delta
$delta = 0.00001;
if (abs(1.00000001 - 1.00000000) < $delta) {
    print("equal enough\n");
}

// PHP auto incorporate a little "fuzz factor" when rounding
// so don't worry about float point inacuracy
round(2.5);
ceil(2.4);
floor(0.5);

