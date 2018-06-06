<?php
/**
 * Main page
 */
ob_start();
require_once("model.php");

// viewable only if user is logged in
session_start();
if (!isset($_SESSION['loggedInUser'])) {
    redirectTo("login.php");
}
ob_flush();

// get main page text
$_SESSION['courseName'] = getConfigProperty('course_name');
$_SESSION['welcome_title'] = getConfigProperty('welcome_title');
$_SESSION['welcome_message'] = getConfigProperty('welcome_message');

include_once("templates/page_head.php");
?>

<div class="container">
    <?php
    include_once("templates/navigation.php");
    ?>

    <content>
        <div class="container-fluid"> 
			<?php
			
			if($_SESSION['isInstructor']  < '3'){
			
			echo("<a href=\"javascript:void(0);\" ID='active' >Active Course</a>");
			
			echo("<table id='active-course-table' class='table-striped table-hover'>");
            echo("    <tr>");
            echo("    <th>Course Name</th>");
            echo("    <th>Instructor</th>");
            echo("    <th>Teach Assistance</th>");
            echo("    <th>Number of Student</th>");
            echo("    <th>Create Date</th>");
			echo("	  <th>Deactive/Edit</th>");
            echo("    </tr>");
			}
			?>
                <?php
				if($_SESSION['isInstructor']  < '2')
					$active = getAllCourseActive();
				if($_SESSION['isInstructor']  == '2')
					$active = getCourseTeachActive($_SESSION['ID']);
				
                while ($row = mysqli_fetch_assoc($active)) { ?>
                <!-- output each row-->
                <tr>
                    <td><?php echo htmlentities($row['name']) ?></td>
                    <td><?php echo htmlentities(getName($row['instructorID'])) ?></td>
                    <td><?php echo htmlentities(getName($row['TAID'])) ?></td>
					<td><?php echo htmlentities(countStudentEnroll($row['CourseID'])) ?></td>
                    <td><?php echo htmlentities($row['TimeCreate']) ?></td>
					<td>
	                    <a href='course_deactive.php?course=<?php echo urlencode($row["name"]) ?>'> <span
			                        class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>
						<a href='setCourse.php?crc=<?php echo urlencode($row["CourseID"]) ?>&view=true'> <span
			                        class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>
                    </td>
					
					<?php } ?>
            <?php if($_SESSION['isInstructor']  < '3')
					echo("</table>"); ?>
			
			
			<?php
			if($_SESSION['isInstructor']  < '3'){
			echo("</br></br></br>");
			echo("<a href=\"javascript:void(0);\" ID='closed' >Closed Course</a>");
			
			echo("<table id='closed-course-table' class='table-striped table-hover'>");
            echo("    <tr>");
            echo("    <th>Course Name</th>");
            echo("    <th>Instructor</th>");
            echo("    <th>Teach Assistance</th>");
            echo("    <th>Number of Student</th>");
            echo("    <th>Create Date</th>");
			echo("	  <th>Reactive/Edit</th>");
            echo("    </tr>");
			}
			?>
                <?php
				
				if($_SESSION['isInstructor']  < '2')
					$active = getAllCourseNotActive();
				if($_SESSION['isInstructor']  == '2')
					$active = getCourseTeachNotActive($_SESSION['ID']);
				
                while ($row = mysqli_fetch_assoc($active)) { ?>
                <!-- output each row-->
                <tr>
                    <td><?php echo htmlentities($row['name']) ?></td>
                    <td><?php echo htmlentities(getName($row['instructorID'])) ?></td>
                    <td><?php echo htmlentities(getName($row['TAID'])) ?></td>
					<td><?php echo htmlentities(countStudentEnroll($row['CourseID'])) ?></td>
                    <td><?php echo htmlentities($row['TimeCreate']) ?></td>
					<td>
	                    <a href='course_reactive.php?course=<?php echo urlencode($row["name"]) ?>'> <span
			                        class='glyphicon glyphicon-ok' aria-hidden='true'></span></a>
						<a href='setCourse.php?crc=<?php echo urlencode($row["CourseID"]) ?>&view=true'> <span
			                        class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a>
                    </td>
					
					<?php } ?>
            <?php if($_SESSION['isInstructor']  < '3')
					echo("</table>"); ?>
				
				
			<?php
			if($_SESSION['isInstructor']  > '2'){
			echo("</br></br></br>");
			echo("<a href=\"javascript:void(0);\" ID='lab' >Labs</a>");
			
			echo("<table id='lab-table' class='table-striped table-hover'>");
            echo("    <tr>");
            echo("    <th>Course Name</th>");
            echo("    <th>Lab Name</th>");
            echo("    <th>status</th>");
			echo("    <th>Mark</th>");
			echo("    <th>View</th>");
            echo("    </tr>");
			
				$info = getLabBySutdent($_SESSION['ID']);
			
				while($row = mysqli_fetch_assoc($info))
				{
					if(mysqli_num_rows(getMark($_SESSION['ID'], $row['ID'])) < 1)
					{
						if($row['Active'] == 1)
						{
							$p = 'In Progess';
							$m = null;
						}
						else
						{
							$p = 'Expired';
							$m = 0;
						}
					}
					else
					{
						if($row['ShowMark'] == 1)
						{
							$p = 'Graded';
							$m = getMark($_SESSION['ID'], $row['ID']);
							$m = mysqli_fetch_assoc($m);
							$m = $m['mark'];
						}
						else
						{
							$p = 'Submit';
							$m = 'Not Graded';
						}
						
					}
						
					echo("<tr>");
					echo("<td>" . getCourseNameByLab($row['ID']) . "</td>");
					echo("<td>" . $row['name'] . "</td>");
					echo("<td>" . $p . "</td>");
					echo("<td>" . $m . "</td>");
					echo("<td>");
					echo("<a href='lab_view.php?id=" . $row['ID'] . "'> <span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> </a>");
					echo("</td>");
					echo("</tr>");
				}
			}
			
			
			?>
        </div>
    </content>

</div> <!-- /container -->

<?php include_once("templates/page_footer.php"); ?>

<?php
	if($_SESSION['isInstructor']  < '3')
	{
		echo("<script>");
		echo("$(document).ready(function () {");		
		echo("\$(\"#active\").click(function(){\$(\"#active-course-table\").toggle();})");
		echo("\n\$(\"#closed\").click(function(){\$(\"#closed-course-table\").toggle();})");
		echo("})</script>");
	}
	
	if($_SESSION['isInstructor']  > '2')
	{
		echo("<script>");
		echo("$(document).ready(function () {");		
		echo("\n\$(\"#lab\").click(function(){\$(\"#lab-table\").toggle();})");
		echo("})</script>");
	}
?>