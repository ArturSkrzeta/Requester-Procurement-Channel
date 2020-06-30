<?php
require "config/config.php";
require "includes/form_handlers/register_handler.php";
require "includes/form_handlers/login_handler.php";
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="static/css/register_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	  <script src="static/js/register.js"></script>
  </head>
  <body>

    <?php
    if(isset($_POST['reg_button'])){
      echo '
      <script>
      $(document).ready(function() {
        $("#first").hide();
        $("#second").show();
      });
      </script>
      ';
    }
    ?>

    <div class="wrapper">

      <div class="login_box">

        <div class="login_header">
          <h1>Welcome to ProcYou</h1>
          <p>Login or sign up below.</p>
        </div>

        <div id="first">
          <form action="register.php" method="POST">
            <input type="email" name="log_em" placeholder="Email Address" value="<?php
            if (isset($_SESSION['log_em'])){
                echo $_SESSION['log_em'];
            }
            ?>" required>
            <br>
            <input type="password" name="log_password" placeholder="Password">
            <br>
            <?php if (in_array('<span class="fail">Email or password incorrect.</span><br>', $error_array)) echo '<span class="fail">Email or password incorrect.</span><br>';?>

            <input type="submit" name="log_button" value="Login">
            <br>

            <a href="#" id="signup" class="signup">Need an account? Register here!</a>
          </form>
        </div>

        <div id="second">
          <form action="register.php" method="POST">

            <input type="text" name="reg_fname" placeholder="First Name" value="<?php
            if (isset($_SESSION['reg_fname'])){
                echo $_SESSION['reg_fname'];
            }
            ?>" required>
            <br>
            <?php if (in_array('<span class="fail">Your first name must be between 2 and 25 characters.</span><br>',$error_array)) echo '<span class="fail">Your first name must be between 2 and 25 characters.</span><br>';?>


            <input type="text" name="reg_lname" placeholder="Last Name" value="<?php
            if (isset($_SESSION['reg_lname'])){
                echo $_SESSION['reg_lname'];
            }
            ?>" required>
            <br>
            <?php if (in_array('<span class="fail">Your last name must be between 2 and 25 characters.</span><br>',$error_array)) echo '<span class="fail">Your last name must be between 2 and 25 characters.</span><br>';?>


            <input type="email" name="reg_em" placeholder="Email" value="<?php
            if (isset($_SESSION['reg_em'])){
                echo $_SESSION['reg_em'];
            }
            ?>" required>


            <input type="email" name="reg_em2" placeholder="Confirm Email" value="<?php
            if (isset($_SESSION['reg_em2'])){
                echo $_SESSION['reg_em2'];
            }
            ?>" required>
            <br>
            <?php if (in_array('<span class="fail">Email already in use.</span><br>',$error_array)) echo '<span class="fail">Email already in use.</span><br>';
            else if (in_array('<span class="fail">Invalid email format.</span><br>',$error_array)) echo '<span class="fail">Invalid email format.</span><br>';
            else if (in_array('<span class="fail">Emails do not match.</span><br>',$error_array)) echo '<span class="fail">Emails do not match.</span><br>';?>


            <input type="password" name="reg_password" placeholder="Password" required>
            <br>

            <input type="password" name="reg_password2" placeholder="Confirm Password" required>
            <br>
            <?php if (in_array('<span class="fail">Given passwords are different.</span><br>',$error_array)) echo '<span class="fail">Given passwords are different.</span><br>';
            else if (in_array('<span class="fail">Only english characters or numbers.</span><br>',$error_array)) echo '<span class="fail">Only english characters or numbers.</span><br>';
            else if (in_array('<span class="fail">Password must be between 5 and 30 characters.</span><br>',$error_array)) echo '<span class="fail">Password must be between 5 and 30 characters.</span><br>';?>

            <input type="submit" name="reg_button" value="Register">
            <br>
            <?php if (in_array('<span class="success">You have been registered.<br> You can login now.</span><br>',$error_array)) echo '<span class="success">You have been registered. You can login now.</span><br>';?>

            <a href="#" id="signin" class="signin">Already have an account? Sign in here!</a>

          </form>
        </div>
      </div>
    </div>

  </body>
</html>
