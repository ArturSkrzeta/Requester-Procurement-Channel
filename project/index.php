<?php
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");
?>

	<div class="wrapper">

		<div class="start">

			<div class="user-details column">
				<a href="<?php echo $userLoggedIn;?>"><img src="<?php echo $user['profile_pic'];?>"></a>
				<div class="user-details-left">
					<a href="<?php echo $userLoggedIn;?>"><?php echo $user['first_name'] . " " . $user['last_name'] . "<br>"; ?></a>
					<?php echo "Posts: " . $user['num_posts']. "<br>"; ?>
				</div>
			</div>

			<div class="main_form">
				<form class="post-form" action="confirmation.php" method="POST">
					<textarea name="post_text" id="post_text" placeholder="Enter your message here..."></textarea><br>
					<input type="submit" name="post" id="post_button" value="Post" onclick="update_posts_count()">
				</form>
			</div>

		</div>

		<div class="main_column column">

			<div class="posts_area">
				<?php
				$post = new Post($con, $userLoggedIn);
				$filtered = False;
				$post->loadPosts($filtered, $userLoggedIn);
				?>
			</div>

		</div>
	</div>

	<script>

		function update_posts_count() {
			if (document.getElementById("post_text").value == "") {
					alert("Provide a message!");
			  	event.preventDefault();
			  }
			}

	</script>

</body>
</html>
