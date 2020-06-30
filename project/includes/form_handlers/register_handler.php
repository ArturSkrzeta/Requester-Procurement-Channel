<?php

//Delcaring variables
$fname = "";
$lname = "";
$em = "";
$em2 ="";
$password = "";
$password2 = "";
$date = "";
$error_array = array();

// $_POST take what was sent in POST form
// if req_button was clicked
if (isset($_POST['reg_button'])){

  //Form values to variables
  $fname = strip_tags($_POST['reg_fname']); //removing tags
  $fname = str_replace(' ', '',$fname); // removing spaces
  $fname = ucfirst(strtolower($fname)); // small -> capitaliezed first
  $_SESSION['reg_fname'] = $fname; // fanme --> session variable

  $lname = strip_tags($_POST['reg_lname']); //removing tags
  $lname = str_replace(' ', '',$lname); // removing spaces
  $lname = ucfirst(strtolower($lname)); // small -> capitaliezed first
  $_SESSION['reg_lname'] = $lname; // lanme --> session variable

  $em = strip_tags($_POST['reg_em']); //removing tags
  $em = str_replace(' ', '',$em); // removing spaces
  $em = ucfirst(strtolower($em)); // small -> capitaliezed first
  $_SESSION['reg_em'] = $em; // em --> session variable

  $em2 = strip_tags($_POST['reg_em2']); //removing tags
  $em2 = str_replace(' ', '',$em2); // removing spaces
  $em2 = ucfirst(strtolower($em2)); // small -> capitaliezed first
  $_SESSION['reg_em2'] = $em2; // em --> session variable

  $password = strip_tags($_POST['reg_password']); //removing tags
  $password2 = strip_tags($_POST['reg_password2']); //removing tags

  $date = date("Y-m-d"); // current date

  //EMAIL CHECK
  if ($em == $em2){
    // validation of email NumberFormatter
    if(filter_var($em, FILTER_VALIDATE_EMAIL)){
      $em = filter_var($em, FILTER_VALIDATE_EMAIL);
      //check if email exists in db
      $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");
      //count the rows returned
      $num_rows = mysqli_num_rows($e_check);
      if ($num_rows > 0){
        array_push($error_array,'<span class="fail">Email already in use.</span><br>');
      }
    }
    else{
      array_push($error_array,'<span class="fail">Invalid email format.</span><br>');
    }
  }
  else{
    array_push($error_array,'<span class="fail">Emails do not match.</span><br>');
  }

  //NAME CHECK
   if (strlen($fname) > 25 || strlen($fname) < 2) {
    array_push($error_array, '<span class="fail">Your first name must be between 2 and 25 characters.</span><br>');
  }

  //LASTNAME CHECK
  if (strlen($lname) > 25 || strlen($lname) < 2) {
    array_push($error_array, '<span class="fail">Your last name must be between 2 and 25 characters.</span><br>');
  }

  //PASSWORD CHECK
  if ($password != $password2){
    array_push($error_array, '<span class="fail">Given passwords are different.</span><br>');
  }
  else{
    if (preg_match('/[^A-Za-z0-9]/',$password)){
      array_push($error_array, '<span class="fail">Only english characters or numbers.</span><br>');
    }
  }

  if (strlen($password) > 30 || strlen($password) < 5){
    array_push($error_array,'<span class="fail">Password must be between 5 and 30 characters.</span><br>');
  }

    //IF NO ERRORS then insert values into db
  if(empty($error_array)){
    $password = md5($password);  //password encryption
    $username = strtolower($fname . "_" . $lname);
    $username_check = mysqli_query($con, "SELECT username FROM users WHERE username='$username'"); //checking if username already exists

    //if username exists append next available number to it
    $i = 0;
    while(mysqli_num_rows($username_check) != 0){
      $i++;
      $username = $username . "_" . $i;
      $username_check = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
    }

    //Profile pic assignment
    $rand = rand(1,2);
    if ($rand == 1)
      $profile_pic = 'static/images/profile_pics/defaults/head_belize_hole.png';
    elseif ($rand == 2)
      $profile_pic = 'static/images/profile_pics/defaults/head_turqoise.png';


    $query = mysqli_query($con, "INSERT INTO users VALUES('','$fname','$lname','$username','$em', '$password', '$date', '$profile_pic', '0','0','no',',')");

    //only one element when registation successful
    array_push($error_array,'<span class="success">You have been registered.<br> You can login now.</span><br>');
    //clear session variables when registration successful
    $_SESSION['reg_fname'] = "";
    $_SESSION['reg_lname'] = "";
    $_SESSION['reg_em'] = "";
    $_SESSION['reg_em2'] = "";

  }
}

?>
