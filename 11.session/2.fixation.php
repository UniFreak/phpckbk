<?php
// Fixation: attacker forces user use a predetermined session ID
//
// Require new session ID to be generated on a regular basis: 30 seconds

ini_set('session.use_only_cookies', true);
session_start();
if (! isset($_SESSION['generate']) || $_SESSION['generated'] < (time()-30)) {
    session_regenerate_id();
    $_SESSION['generated'] = time();
}