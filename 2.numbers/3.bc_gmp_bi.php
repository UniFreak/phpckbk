<?php
// these three are used for very large or small numbers

// BCMath is limited to basic arithmetic
printf("%d\n", bcadd('1234567812345678', '8765432187654321'));

// GMP functions return only resources
printf("%d\n", gmp_add(2, 2));

// PECL's big_int lib is fater than BCMath and almost as powerful as GMP
// But licensed under BSD-style license