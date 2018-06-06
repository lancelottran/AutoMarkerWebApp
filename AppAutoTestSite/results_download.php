<?php

ob_start();
require_once("model.php");

//only accessible if logged in as instructor
session_start();
if (!isset($_SESSION['loggedInUser']) || $_SESSION['isInstructor'] != '1') {
	redirectTo("index.php");
}

header('Content-type: text/csv');
header('Content-disposition: attachment;filename=Results.csv');

// open file using stdout
$out = fopen("php://output", "w");


//perform query
$results = getResultsForDownload();
if (!$results) {
	echo "Retrieving results failed.";
}

// column headings
fputcsv($out, array("Lab Name", "Username", "Test Case", "Result"));

// write db query result
while ($row = mysqli_fetch_assoc($results)) {
	fputcsv($out, $row);
}
fclose($out);

?>
