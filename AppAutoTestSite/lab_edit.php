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
	$id = $_POST['id'];
	$newid = $_POST['newLabId'];
	$newdesc = $_POST['newLabDesc'];

	modifyLab($id, $newid, $newdesc);
	redirectTo("labs_manage.php");
}
else if (!isset($_GET['id'])) {
	// must pass lab id to modify
    redirectTo('index.php');
}

$id = $_GET['id'];

$currentLab = getLabById($id);



ob_flush();

include_once("templates/page_head.php");
?>

<div class="container">
    <?php
    include_once("templates/navigation.php");
    ?>

    <content>
        <!-- edit lab form -->
        <form class="account-form form-signin" action="lab_edit.php" method="post">
            <h2 class="form-signin-heading"> Edit Lab <?php echo $currentLab['id']; ?> </h2>
            <!-- also post current name for new page to access -->
            <input type="hidden" name="id" value="<?php echo $currentLab['id']; ?>">
            <label for="newLabId" class="sr-only">Name</label>
            <input type="number" name="newLabId" class="form-control" value="<?php echo $currentLab['id']; ?>"
                   placeholder="Lab Number"
                   autofocus>
            <label for="newLabDesc" class="sr-only">Description</label>
            <textarea name="newLabDesc" class="form-control"> <?php echo $currentLab['description'] ?></textarea>

            <br>
            <button id="edit-lab-back-button" class="btn btn-primary" type="button" name="done">Back</button>
            <button class="btn btn-primary" type="submit" name="submit">Submit Changes</button>
        </form>
        <br>
    </content>

</div>
<!-- /container -->

<?php include_once("templates/page_footer.php"); ?>

<script>
    $(document).ready(function () {
        console.log('load');
        $('#edit-lab-back-button').click(function () {
            window.open('labs_manage.php', '_self');
        })
    })
</script>
