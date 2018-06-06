<?php
/**
 * Handles upload of a source file for lab submission.
 * Renames uploaded file for handling by test script.
 * @param $file file to be uploaded
 * @param $lab_id id number of lab for submission
 * @param $username name of user
 * @return array
 */
function uploadFile($file, $lab_id, $username)
{
    
	// get file upload location
	require_once("model.php");
	$target_dir = getConfigProperty('upload_directory');

	// rename uploaded file with username and lab id
	$file = $username . "_Lab_" . $lab_id . "." . pathinfo($_FILES["fileToUpload"]["name"])['extension'];
    
	$target_file = $target_dir . $file;

	//if file already exists
	if (file_exists($target_file)) {
		unlink($target_file);
	}

	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		return array('success' => false, 'message' => 'File too large');

	}

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		//$result = runTests($target_file); // run tests for that filename
        $result = runTests($file); // run tests for that filename
		return array('success' => true, 'message' => "The file has been uploaded successfully.", 'scriptResult' => $result);
	} else {
		return array('success' => false, 'message' => "Sorry, there was an error uploading your file.");
	}
}

function runTests($filename)
{
	//sleep(2);
    chdir('cd /Users/Shared/AndroidTestSuite');
	$script = getConfigProperty('script_location');
    //echo "exec = " . $script . ' ' . $filename;
	$result = exec($script . ' ' . $filename);
    //echo " ---- RES = " . $result;
	chdir('cd /Users/Shared/AndroidTestSuite/AppAutoTestSite');
    return $result;
    
}
?>
