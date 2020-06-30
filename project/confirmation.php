<?php
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");
if (isset($_POST['post'])) {

	if ($_POST['post_text'] != "Enter your message here...") {
		$post = new POST($con, $userLoggedIn);
		$post->submitPost($_POST['post_text'], 'none');
	}
}

?>


<div class="wrapper">
  <div class="main_column column">
    <div style="margin: 10px 0 10px 20px;"
      <p>Done!</p>
      <a href="index.php"><< Go to Main Page</a>
    </div>
  </div
</div>

<!-- <div class="wrapper">
  <div class="main_column column">
    <div style="margin: 10px 0 10px 20px;"
      <p>Enter the message before publishing post</p>
      <a href="index.php"><< Go to Main Page</a>
    </div>
  </div
</div> -->

</body>
</html>
