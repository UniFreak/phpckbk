<?php
// Output Buffer
// ===============================================================
//      manually                global on
//      -------------------------------
//      ob_start(callback)      output_buffering=one
//      ob_end_flush()          output_handler=callback

// Callback is useful for postprocessing all page content, such as hiding email
// address

function mangle_email($s) {
    return preg_replace('/([^@\s]+)@([a-z0-9]+\.)+[a-z]{2,}/is', '<$1@...>', $s);
}

ob_start('mangle_email');
?>

I would like spam sent to ronald@example.com!

<?php
ob_end_flush();

// Same thing can be done by configuration:
// output_buffering=On
// output_handler=mangle_email