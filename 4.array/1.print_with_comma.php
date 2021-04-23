<?php
function array_to_comma_string($arr) {
    switch (count($arr)) {
        case 0:
            return '';
            break;
        case 1:
            return reset($arr); // array[0] may be undefined,
                                // so use reset() to return first element
        case 2:
            return join(' and ', $arr);
        default:
            $last = array_pop($arr);
            return join(', ', $arr) . ", and $last";
            break;
    }
}

print_r(array_to_comma_string(['Apple', 'Orange', 'Other fruites']));