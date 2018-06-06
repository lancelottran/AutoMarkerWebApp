<?php

//returns a database connection
function connectToDb()
{

	// db for production
	$host = "127.0.0.1";
	$user = "appautotest";
	$pass = "mobileappstru";
	$dbname = "appautotest";
	$port = "3306";

	// development
//	$host = "127.0.0.1";
//	$user = "root";
//	$pass = "";
//	$dbname = "appautotest";
//	$port = "3306";

	// connect to the database
	$connection = mysqli_connect($host, $user, $pass, $dbname, $port);
	if (mysqli_connect_errno()) {
		die('Could not connect: ' . mysqli_connect_error() . ' error number: ' . mysqli_connect_errno() . '<br>');
	}
	return $connection;
}