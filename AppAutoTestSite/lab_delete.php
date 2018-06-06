<?php
/**
 * Deletes a lab
 */
ob_start();
require_once("model.php");

//only accessible if logged in as instructor
session_start();
if (!isset($_SESSION['loggedInUser']) || $_SESSION['isInstructor'] != '1') {
    redirectTo("index.php");
}

$lab = $_GET['id'];
echo $lab;
deleteLab($lab);

redirectTo("labs_manage.php");

ob_flush();
?>