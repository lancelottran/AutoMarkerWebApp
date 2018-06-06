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

ob_flush();

include_once("templates/page_head.php");

?>

<div class="container">
    <?php
    require("templates/navigation.php");
    ?>

    <content>
        <div class="container-fluid">

            <h2>Manage Labs</h2>
            <br>

	        <p>
		        Click on a lab's name to see the student view for that lab. You may add, edit, or delete labs from this
		        page.
	        </p>
            <?php
            $result = getLabs();
            ?>
            <a href="lab_add.php"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp;Add Lab</a>
            <table id="labs-table" class='table-striped table-hover'>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <th>
                            <a href="lab_view.php?id=<?php echo htmlentities($row['id']) ?>"><?php echo "Lab " . htmlentities($row['id']) ?></a>
                        </th>
                        <td>
                            <!--                            TODO confirm deletion?-->
                            <a href='lab_delete.php?id=<?php echo urlencode($row["id"]) ?>'> <span
                                    class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>
                            <a href='lab_edit.php?id=<?php echo urlencode($row["id"]) ?>'> <span
                                    class='glyphicon glyphicon-pencil' aria-hidden='true'></span> </a>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2"><?php echo htmlentities($row['description']) ?></td>

                    </tr>
                <?php } ?>
            </table>
            <br>


            <?php
            // release returned data
            mysqli_free_result($result);
            // close database connection
            mysqli_close($connection);
            ?>
        </div>
    </content>

</div>
<!-- /container -->

<?php include_once("templates/page_footer.php"); ?>