<?php
// By default, variable_order=EGPCS. And later will overwrite former
//
// While $_REQUEST can be convenient, it's better to look in more detailed array
// directly. That way, you know exactly what you're getting and don't have to
// worry that a change in variable_order affects the behavior of your program

// register_globals better set off

// Different form element cause different behaviro in GET and POST when left empty:
// - blank text boxes/area/file_upload: value is a zero-length string
// - unchecked checkbox/radio button: don't produce any elements
//
// @BP: To make code robust, always check element exists before applying other
// validation. Additionally, if validation assume element is array of values,
// ensure that the value really is array

// XSS: htmlentities() & htmlspecialchars()
// ===============================================================


// Validate Form Input
// ===============================================================
// existence
if (! (filter_has_var(INPUT_POST, 'flavor')
    && (strlen(filter_input(INPUT_POST, 'flavor')) > 0 ))) {
    print 'Must enter flavor.';
}

// optional, but must more than 5 char long if supplied
if (filter_has_var(INPUT_POST, 'color')
    && (strlen(filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING)) <= 5)) {
    echo 'Color must be more than 5 chars.';
}

// exists and is array
if (! (filter_has_var(INPUT_POST, 'choices')
    && filter_input(INPUT_POST, 'choices', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY))) {
    echo 'Must select some choices.';
}

// is number
if (! filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT)) {
    echo 'Must be number';
}

// is email
if (! filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
    echo 'Must be email';
}

// drop-down menus: when no default choice specified, first choice is used
$choices = ['Eggs', 'Toast', 'Coffee'];
if (! in_array($_POST['food'], $choices)) { // form name=food
    echo "Must select a valid choice."
}

// radio-button: when no default choice specified, no choice is used as default

// Credit card: Luhn algorithm
function is_valid_credit_car($s) {
    // remove non-digits and reverse
    $s = strrev(preg_replace('/[^\d]', '', $s));
    // checksum
    $sum = 0;
    for ($i = 0, $j = strlen($s); $i < $j; $i++) {
        // use even digits as-is
        if (($i % 2) == 0) {
            $val = $s[$i];
        } else {
            // double odd digits and substract 9 if greater than 9
            $val = $s[$i] * 2;
            if ($val > 9) {$val -= 9;}
        }
        $sum += $val;
    }
    // valid if sum is a multiple of ten
    return (($sum % 10) == 0);
}

