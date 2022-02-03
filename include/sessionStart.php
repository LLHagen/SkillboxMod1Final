<?php

if (!isset($_SESSION)) {
    ini_set('session.gc_maxlifetime', 86400);
    ini_set('session.cookie_lifetime', 86400);
    session_set_cookie_params(0);
    session_start();
}