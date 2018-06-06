<?php

session_start();

//only accessible if logged in
if (!isset($_SESSION['loggedInUser'])) {
	redirectTo("index.php");
}

clearFlag("result_ready");
