<?php
// 2 decimal digit, thousand seperator is ',' and decimal point char is .
printf("%s\n", number_format(1234.56, 2, ',', '.'));

// don't know ahead how many decimal digits
$france = new NumberFormatter('fr-FR', NumberFormatter::DEFAULT_STYLE);
printf("%s\n", $france->format(1234.56));

// Currency
// ===============================================================
$usa = new NumberFormatter('en-US', NumberFormatter::CURRENCY);
printf("%s\n", $usa->format(1234.56));
printf("%s\n", $usa->formatCurrency(1234.56, 'EUR'));