<?php
ob_start();
require_once("model.php");

session_start();

session_unset();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 1000, '/');
}

session_destroy();

redirectTo("index.php");

?>