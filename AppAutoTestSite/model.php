<?php

// 302 redirect
function redirectTo($page)
{
    header("Location: " . $page);
}

require_once('database_connection.php');

function getConfigProperty($property)
{
    $connection = connectToDb();
    $query = "SELECT value FROM config WHERE property = '$property'";
    $result = mysqli_fetch_assoc(mysqli_query($connection, $query));
    return $result['value'];
}

function setConfigProperty($property, $value)
{
    $connection = connectToDb();
	$value = htmlspecialchars($value);
    $query = "UPDATE config SET value = '$value' where property = '$property';";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Updating config failed" . mysqli_error($connection));
    }
    mysqli_close($connection);
}

// queries the database for all users
function findAllUsers()
{
    $connection = connectToDb();
    $query = "SELECT * FROM user ORDER BY username";
    $users = mysqli_query($connection, $query);
    return $users;
}

// queries the database for the specified username
function findUser($username)
{
    $connection = connectToDb();
    $userid = mysqli_real_escape_string($connection, $username);
    $query = "SELECT * FROM user WHERE username = '{$userid}' LIMIT 1";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        echo "findUserById {$username} failed";
    }
    if ($user = mysqli_fetch_assoc($result)) {
        return $user;
    } else {
        return null;
    }
}

function findPrivilige($id)
{
	$connection = connectToDb();
    $id = mysqli_real_escape_string($connection, $id);
    $query = "SELECT * FROM privilege WHERE ID = '$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        echo "findPrivilige failed";
    }
    if ($user = mysqli_fetch_assoc($result)) {
        return $user;
    } else {
        echo "failed fetch";
    }
}

// adds a user to the database
function addUser($username, $firstName, $lastName, $password)
{
    $connection = connectToDb();
    $usr = mysqli_real_escape_string($connection, $username);
	$fn = mysqli_real_escape_string($connection, $firstName);
	$ln = mysqli_real_escape_string($connection, $lastName);
	$pw = mysqli_real_escape_string($connection, $password);

    // add new user
	$query = "INSERT INTO user (username, first_name, last_name, password)
                VALUES ('$usr', '$fn', '$ln', '$pw'); ";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Adding user failed");
    }
    mysqli_close($connection);
}

// deletes a user from the database
function deleteUser($user)
{
    $connection = connectToDb();
    $username = mysqli_real_escape_string($connection, $user);

    $query = "DELETE FROM user WHERE username = '$username';";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Deleting user failed" . mysqli_error($connection));
    }

    mysqli_close($connection);
}

// replaces a user's information in the database with the new information provided
function modifyUser($user, $newPass)
{
    $connection = connectToDb();
    $user = mysqli_real_escape_string($connection, $user);
    $newPass = mysqli_real_escape_string($connection, $newPass);

    $query = "UPDATE user SET password = '$newPass' WHERE username = '$user' LIMIT 1;";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Updating user failed" . mysqli_error($connection));
    }
    mysqli_close($connection);
}

// retrieves all labs
function getLabs()
{
    $connection = connectToDb();
    $query = "SELECT * FROM lab ORDER BY id";
    $labs = mysqli_query($connection, $query);
    return $labs;

}

function getResultsForDownload()
{
	$connection = connectToDb();
	$query = "SELECT lab.id, username, test_case_num, result FROM result JOIN lab ON result.lab_id = lab.id ORDER BY username";
	$results = mysqli_query($connection, $query);
	return $results;
}

function getLabById($id)
{
    $connection = connectToDb();
    $id = mysqli_real_escape_string($connection, $id);
    $query = "SELECT * FROM lab WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        echo "find Lab {$id} failed";
    }
    if ($lab = mysqli_fetch_assoc($result)) {
        return $lab;
    } else {
        return null;
    }
}



function addLab($id, $desc)
{
    $connection = connectToDb();
    $id = mysqli_real_escape_string($connection, $id);
	$desc = mysqli_real_escape_string($connection, $desc);
    $query = "INSERT INTO lab (id, description)
                VALUES ('$id', '$desc'); ";
	$result = mysqli_query($connection, $query);
    if (!$result) {
	    die("Adding lab failed" . mysqli_error($connection));
    }

    mysqli_close($connection);
}

function deleteLab($id)
{
    $connection = connectToDb();
	$id = mysqli_real_escape_string($connection, $id);

	$query = "DELETE FROM lab WHERE id = '$id';";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Deleting lab " . $id . " failed" . mysqli_error($connection));
    }

    mysqli_close($connection);
}

function modifyLab($id, $newid, $newdesc)
{
    $connection = connectToDb();
    $id = mysqli_real_escape_string($connection, $id);
    $newid = mysqli_real_escape_string($connection, $newid);
    $newdesc = mysqli_real_escape_string($connection, $newdesc);

    $query = "UPDATE lab SET id = '$newid', description = '$newdesc' WHERE id = '$id' LIMIT 1;";
    var_dump($query);
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Updating lab failed" . mysqli_error($connection));
    }
    mysqli_close($connection);
}

function addTestCasesForLab($labId, $testCasesArray)
{

	$connection = connectToDb();
	$labId = mysqli_real_escape_string($connection, $labId);

	for ($i = 0; $i < count($testCasesArray); $i++) {
		$testCaseNumber = $i + 1;
		$testCaseName = $testCasesArray[$i][$testCaseNumber . 'name'];
		$testCaseName = mysqli_real_escape_string($connection, $testCaseName);

		$testCaseDescription = $testCasesArray[$i][($testCaseNumber . 'description')];
		$testCaseDescription = mysqli_real_escape_string($connection, $testCaseDescription);

		$query = "INSERT INTO testcase (lab_id, test_case_num, name, description) VALUES ('$labId', '$testCaseNumber', '$testCaseName', '$testCaseDescription');";
		$result = mysqli_query($connection, $query);
		if (!$result) {
			die("Adding test case failed" . mysqli_error($connection));
		}
	}

	mysqli_close($connection);

}

function getTestCasesForLab($labId)
{
	$connection = connectToDb();
	$labId = mysqli_real_escape_string($connection, $labId);
	$query = "SELECT test_case_num, name, description FROM testcase WHERE lab_id = '$labId'";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "getting test cases failed";
	}
	return $result;
}

function getTestCaseResult($labid, $testCaseNumber, $username)
{
	$connection = connectToDb();
	$query = "SELECT result FROM result WHERE lab_id='$labid' AND test_case_num = '$testCaseNumber' AND username = '$username'";
	$results = mysqli_query($connection, $query);
	return $results;
}

function isFlagSet($flagName)
{
	$connection = connectToDb();
	$query = "SELECT value FROM flags WHERE name='$flagName'";
	$results = mysqli_query($connection, $query);
	return mysqli_fetch_assoc($results)['value'];
}

function clearFlag($flagName)
{
	$connection = connectToDb();
	$query = "UPDATE flags SET value = 0 where name = '$flagName';";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		die("Updating flags failed" . mysqli_error($connection));
	}
	mysqli_close($connection);
}

?>
