<?php
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");

// porfile name argument in htaccess file
if (isset($_GET['profile_username'])) {
	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
	$user_array = mysqli_fetch_array($user_details_query);
}

?>

<div class="wrapper">

		<div class="user-details column" style="height: 300px;">
			<a href="<?php echo $userLoggedIn;?>"><img src="<?php echo $user['profile_pic'];?>"></a>
			<div class="user-details-left">
				<a href="<?php echo $userLoggedIn;?>"><?php echo $user['first_name'] . " " . $user['last_name'] . "<br>"; ?></a>
				<?php echo "Posts: " . $user['num_posts'] . "<br>";?>
			</div>
			<hr>
			<h4>Your Tasks:</h4>
		</div>


		<div class="user-profile">

			<?php
			$post = new Post($con, $userLoggedIn);
			$filtered = True;
			$post->loadPosts($filtered, $userLoggedIn);
			?>

		</div>


</div>


</body>
</html>
