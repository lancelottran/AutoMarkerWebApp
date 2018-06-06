<?php
/**
 * Lab management page
 */

ob_start();
require_once("model.php");

//only accessible if logged in as instructor
session_start();
if (!isset($_SESSION['loggedInUser']) || $_SESSION['isInstructor'] != '1') {
    redirectTo("index.php");
}

if (isset($_POST['submit'])) {
    // form was submitted
    setConfigProperty('course_name', $_POST['course_name']);
    setConfigProperty('welcome_title', $_POST['welcome_title']);
    setConfigProperty('welcome_message', $_POST['welcome_message']);
    redirectTo("index.php");
}

ob_flush();

include_once("templates/page_head.php");

?>


<div class="container">
    <?php
    require("templates/navigation.php");
    ?>

    <content>
        <div class="container-fluid">

            <h2>Settings</h2>
            <br>

            <form class="form-horizontal" action="settings.php" method="post">
                <div class="form-group">
                    <label for="courseNameTextBox">Course Name</label>
                    <input id="courseNameTextBox" name="course_name" type="text" class="form-control"
                           value="<?php echo getConfigProperty('course_name'); ?>">
                </div>
                <div class="form-group">
                    <label for="welcomeTitleTextBox">Subtitle</label>
                    <input id="welcomeTitleTextBox" name='welcome_title' type="text" class="form-control"
                           value="<?php echo getConfigProperty('welcome_title'); ?>">
                </div>
                <div class="form-group">
                    <label for="welcomeText">Welcome Message</label>
	                <textarea id="welcomeText" name="welcome_message" rows="4"
	                          class="form-control"><?php echo getConfigProperty('welcome_message'); ?></textarea>
                </div>
                <button class="btn btn-primary" type="submit" name="submit">Submit Changes</button>
            </form>


        </div>
    </content>

</div>
<!-- /container -->

<?php include_once("templates/page_footer.php");