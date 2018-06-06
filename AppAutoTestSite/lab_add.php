<?php
ob_start();
require_once("model.php");

//only accessible if logged in as instructor
session_start();
if (!isset($_SESSION['loggedInUser']) || $_SESSION['isInstructor'] != '1') {
    redirectTo("index.php");
}

if (isset($_POST['submit'])) {
    // form was submitted
    $id = $_POST['inputLabId'];
    $description = $_POST['inputLabDesc'];
    // check for duplicate lab
	// lab with that name exists
    if (getLabById($id) != null) {
        $msg = "Can't add Lab {$id}, already exists.";
    } else {
	    // add lab
	    $testCases = array();
	    for ($i = 1; $i <= $_POST['numTestCases']; $i++) {
		    array_push($testCases, array($i . 'name' => $_POST['testCase' . $i . 'Name'], $i . 'description' => $_POST['testCase' . $i . 'Description']));
	    }
	    addLab($id, $description);
	    addTestCasesForLab($id, $testCases);
        $msg = "Lab {$id} added.";
    }
} else {
    // form was not submitted (GET request)
    $msg = "Add Lab";
}

ob_flush();

include_once("templates/page_head.php");
?>

<div class="container">
    <?php
    include_once("templates/navigation.php");
    ?>

    <content>
        <!-- add lab form -->
        <form class="account-form form-signin" action="lab_add.php" method="post">
            <h2 class="form-signin-heading"> <?php echo $msg; ?> </h2>
            <label for="inputLabId" class="sr-only">Username</label>
            <input type="number" name="inputLabId" class="form-control" placeholder="Lab Number" required autofocus>
            <label for="inputLabDesc" class="sr-only">Description</label>
            <!-- TODO textarea?-->
            <input type="text" name="inputLabDesc" class="form-control" placeholder="Description" required>


	        <div id="test-case-selection" class="form-group">
		        <br>
		        <h5>Number of Test Cases</h5>
		        <select class="form-control" name="numTestCases">
			        <?php
			        for ($i = 1; $i <= 5; $i++) {
				        echo "<option value='" . $i . "''>" . $i . "</option>";
			        } ?>
		        </select>
	        </div>

	        <div id="test-cases">
		        <!--			test case imputs are appended here-->
	        </div>

	        <br>
            <button id="done-adding-button" class="btn btn-primary" type="button" name="done">Done</button>
            <button class="btn btn-primary" type="submit" name="submit">Add Lab</button>

        </form>
        <br>

    </content>

</div>
<!-- /container -->

<?php include_once("templates/page_footer.php"); ?>

<script>
    $(document).ready(function () {
        console.log('load');
        $('#done-adding-button').click(function () {
            window.open('labs_manage.php', '_self');
        });

	    var numTestCases = $('select').val();
	    numTestCasesChanged();

	    $('select').on('change', function () {
		    numTestCases = $('select').val();
		    numTestCasesChanged();
	    });

	    function numTestCasesChanged() {
		    $();
		    $('#test-cases').empty();
		    for (var i = 1; i <= numTestCases; i++) {
			    var e = $('#test-cases');
			    e.append("<br>");
			    e.append("<h5> Test Case " + i + "</h5>");
			    e.append("<input type='text' name='testCase" + i + "Name' class='form-control' placeholder='Name' required>");
			    e.append("<textarea rows='2' name='testCase" + i + "Description' class='form-control' placeholder='Description' required>");

		    }
	    }

    })
</script>
