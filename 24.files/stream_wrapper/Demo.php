<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ViewStream.php';

class IdiotSavant {
    public function __construct() {
        if (! in_array('view', stream_get_wrappers())) {
            stream_wrapper_register('view', 'ViewStream');
        }
    }

    public function render($filename) {
        include 'view://' . dirname(__FILE__) . DIRECTORY_SEPARATOR
                . $filename . '.html';
    }
}

$view = new IdiotSavant();
$view->hello = 'Hello, World!';
$view->render('Template');