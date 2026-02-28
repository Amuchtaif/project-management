<?php
// public/index.php

if (!session_id()) {
    // Set session lifetime to 3 hours (10800 seconds)
    ini_set('session.gc_maxlifetime', 10800);
    session_set_cookie_params(10800);
    session_start();
}

require_once '../config/config.php';
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';
require_once '../app/helpers/Flasher.php';

$app = new App();
