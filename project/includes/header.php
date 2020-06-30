<?php
require "config/config.php";
//Session variable set once user logged in
if (isset($_SESSION['username'])){
  $userLoggedIn = $_SESSION['username'];
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
  $user = mysqli_fetch_array($user_details_query);
  $userPosition = $user['position'];
}
else{
  header("Location: register.php");
}
?>

<html>
<head>
	<title>ProcYou</title>
  <!-- JavaScript -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="static/js/bootstrap.js"></script>
  <script src="static/js/bootbox.min.js"></script>
  <!-- CSS -->
  <!-- bootstrap styles here -->
  <link rel="stylesheet" type="text/css" href="static/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="static/css/style.css">



  </script>
</head>
<body>

  <div class="top-bar">
    <div class="logo">
      <a href="index.php">ProcYou</a>
    </div>

    <div class="search">

      <form action="search.php" method="GET" name="search_form">
        <input type="text" onkeyup="getLiveSearchUser(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">
        <div class="button_holder">Search</div>
      </form>

      <div  class="search_results"></diV>
      <div  class="search_results_footer_empty"></diV>


    </div>

    <nav>
      <a href="<?php echo $userLoggedIn;?>"><?php echo $user['first_name']; ?></a>
      <a href="includes/handlers/logout.php">Logout</a>
    </nav>
  </div>
