<?php
ob_start();
require_once("model.php");

// form was submitted
if (isset($_POST['submit'])) {

    $username = $_POST['inputUsername'];
	$passwordEntered = sha1($_POST['inputPassword']);

    // find user in db
    $found_user = findUser($username);

    if (!empty($found_user['username'])) {
        // password matches
        $logged_in = ($passwordEntered == $found_user['password']) ? true : false;
        $privilige = findPrivilige($found_user['ID']);
        if ($logged_in) {
            session_start();
            $_SESSION['loggedInUser'] = $found_user['username'];
            $_SESSION['isInstructor'] = $privilige['ID'];
            redirectTo("index.php");
        } else {
//            echo "incorrect pass";
            $msg = "Incorrect Username/Password";
        }
    } //no user or incorrect password
    else {
//        echo "incorrect user";
        $msg = "Incorrect Username/Password";
    }
} // form was not submitted (GET request)
else {
    $msg = "Please Log In";
}

include_once("templates/page_head.php");
ob_flush();
?>

<div class="container">
    <?php
    include_once("templates/navigation.php");
    ?>

    <content>

        <!-- login form -->

        <form class="account-form form-signin" action="login.php" method="post">
            <h2 class="form-signin-heading"> <?php echo $msg ?> </h2>
            <label for="inputUsername" class="sr-only">Username</label>
            <input type="text" name="inputUsername" class="form-control" placeholder="Username"
                   value="<?php if (isset($username)) echo htmlentities($username) ?>" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="inputPassword" class="form-control" placeholder="Password" required>
            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign in</button>
        </form>

    </content>

</div> <!-- /container -->

<?php include_once("templates/page_footer.php"); ?>
