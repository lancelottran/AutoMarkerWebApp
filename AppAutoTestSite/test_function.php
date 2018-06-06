<?php
require_once("model.php");


    $username = 'admin';
	$passwordEntered = sha1('123456');

    // find user in db
    $found_user = findUser($username);

    if (!empty($found_user['username'])) {
        // password matches
        $logged_in = ($passwordEntered == $found_user['password']) ? true : false;
        $privilige = findPrivilige($found_user['ID']);
        echo($privilige['ID']);
	}
	else
	{
		echO('failed');
	}



?>
