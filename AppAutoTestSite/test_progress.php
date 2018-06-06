<?php
header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");

//only accessible if logged in
if (!isset($_SESSION['loggedInUser'])) {
	redirectTo("index.php");
}

require_once("model.php");

if (isFlagSet('script_running')) {
	// script is already running
	echo "data: waiting\n";
} else if (!isFlagSet('script_running') && !isFlagSet('results_ready')) {
	echo "data: run\n";
} else if (isFlagSet('results_ready')) {
	clearFlag('results_ready');
	echo "data: result ready\n";
}

echo "\n";
ob_flush();
flush();