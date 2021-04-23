<?php
// Decide what to do
session_start();

// figure out what stage to use
if (($_SERVER['REQUEST_METHOD'] == 'GET') || (! isset($_POST['stage']))) {
    $stage = 1;
} else {
    $stage = (int) $_POST['stage'];
}

// ensure stage isn't too big or small
$stage = max($stage, 1);
$stage = min($stage, 3);

// save submitted data
if ($stage > 1) {
    foreach ($_POST as $key => $val) {
        $_SESSION[$key] = $val;
    }
}
include __DIR__ . "/stage_$stage.php";