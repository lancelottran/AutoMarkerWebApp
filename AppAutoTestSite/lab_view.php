<?php
 

ob_start();
require_once("model.php");

session_start();

//only accessible if logged in
if (!isset($_SESSION['loggedInUser'])) {
	redirectTo("index.php");
}

// must pass lab id
if (!isset($_GET['id'])) {
	redirectTo('index.php');
}

$lab_id = $_GET['id'];
// was submitted for file upload
if (isset($_POST['submit']) && $_POST['command'] == 'upload') {
	require_once("file_upload.php");
	$uploadStatus = uploadFile(basename($_FILES["fileToUpload"]["name"]), $lab_id, $_SESSION['loggedInUser']);
	$uploadWasSuccessful = $uploadStatus['success'];
	$uploadMessage = $uploadStatus['message'];
	$scriptResult = $uploadStatus['scriptResult'];
}

$currentLab = getLabById($lab_id);

ob_flush();

include_once("templates/page_head.php");
?>

<div class="container" xmlns="http://www.w3.org/1999/html">
    <?php
    include_once("templates/navigation.php");
    ?>

    <content>


	    <h1><?php echo "Lab " . $currentLab['id'] ?></h1>

	    <p><?php echo $currentLab['description'] ?>

        <div>
            <button type="button" id="upload_file_button" class="btn btn-primary"
                    data-toggle="modal" data-target="#uploadFileModal"><span class="glyphicon glyphicon-open"> Upload File
            </button>
        </div>

	    <!-- modal shown when upload button pushed -->
        <div id="uploadFileModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Upload File</h4>
                    </div>
                    <div class="modal-body">
	                    <b>Select a file to upload:</b>

	                    <form action="lab_view.php?id=<?php echo $lab_id ?>" method="post"
	                          enctype="multipart/form-data">

		                    <input type="hidden" name="command" value="upload">
		                    <input type="file" name="fileToUpload" id="fileToUpload">
		                    <br>
		                    <input type="submit" class="btn btn-primary" value="Upload File" name="submit">
                        </form>
                    </div>
                    <div class="modal-footer">
	                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

	    <!-- File upload result message-->
	    <?php
	    if (isset($uploadStatus)) {
		    if ($uploadWasSuccessful) {
			    echo "<div class='alert alert-success upload-result-alert' role='alert'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><center>{$uploadMessage}</center></div>";
                echo "<script>console.log('{$scriptResult}');</script>";
		    } else {
			    echo "<div class='alert alert-danger upload-result-alert' role='alert'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><center>{$uploadMessage}</center></div>";
		    }
	    }
	    ?>

	    <!-- Test progress message -->
	    <div id="TestProgressModal" class="modal fade" role="dialog">
		    <div class="modal-dialog">
			    <div class="modal-content">
				    <div class="modal-body">
					    <div id="test-progress" class="progress">
						    <div class="progress-bar progress-bar-striped active" role="progressbar" data-keyboard
						         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
							    Please Wait
						    </div>
					    </div>

				    </div>
			    </div>
		    </div>
	    </div>

	    <br>
	    <h4>Test Case Results</h4>
	    <p>
		    <?php
		    /**
		     * check for a file with results for that lab and user, display file contents
		     */
		    require_once('TestResult.php');
		    $results = new TestResult($_SESSION['loggedInUser'], $lab_id);

		    $results = explode("\n", $results->GetResultsFromFile());
		    for ($i = 0; $i < count($results); $i++) {
			    echo $results[$i] . "<br>";
		    }
		    1

		    ?>
	    </p>
    </content>

</div>
<!-- /container -->

<?php include_once("templates/page_footer.php"); ?>

<script>
    $(document).ready(function () {

	    <?php
		if (isset($uploadStatus) && $uploadWasSuccessful) { ?>

	    if (typeof(EventSource) !== "undefined") {
		    //console.log('listening for updates');
		    var source = new EventSource('run_tests.php');
		    source.onmessage = function (event) {
			    console.log(event.data);
			    if (event.data == "result ready") {
				    // display results
				    window.location.href = window.location.pathname + window.location.search;
			    }

			    else {
				    $("#TestProgressModal").modal({backdrop: "static"});

			    }
		    }
	    }
	    else {
		    alert("Your browser is not supported.");
	    }
	    <?php
	    }?>
    });

</script>
