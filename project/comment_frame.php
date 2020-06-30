<?php
require "config/config.php";
include("includes/classes/User.php");
include("includes/classes/Post.php");
//Session variable set once user logged in
if (isset($_SESSION['username'])){
  $userLoggedIn = $_SESSION['username'];
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
  $user = mysqli_fetch_array($user_details_query);
}
else{
  header("Location: register.php");
}
?>

<html lang="en" dir="ltr">
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="static/js/bootstrap.js"></script>
    <script src="static/js/bootbox.min.js"></script>
    <!-- CSS -->
    <!-- bootstrap styles here -->
    <link rel="stylesheet" type="text/css" href="static/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="static/css/style.css">
  </head>
  <body>

    <?php
    // Getting post id
    if (isset($_GET['post_id'])) {
      $post_id = $_GET['post_id'];
    }

    // Getting post data
    $user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
    $row = mysqli_fetch_array($user_query);

    //the comment is posted to who added a post
    $posted_to = $row['added_by'];

    // IF SUBMIT BUTTON IN COMMENT CLICKED
    // INSRTING POST INTO DB
    if (isset($_POST['postComment' . $post_id])) {
      $comment_body = $_POST['comment_body'];
      if (strlen($comment_body) != 0) {
      $comment_body = mysqli_real_escape_string($con, $comment_body);
      $date_time_now = date("Y-m-d H:i:s");
      $insert_post = mysqli_query($con, "INSERT INTO comments VALUES('','$comment_body', '$userLoggedIn', '$posted_to','$date_time_now','no','$post_id')");
      }
    }
    ?>

    <div class="comment_container">
      <form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
        <textarea name="comment_body" id="comment_body<?php echo $post_id; ?>"></textarea>
        <input type="submit" name="postComment<?php echo $post_id; ?>" value="Post" onclick="update_comments_count()">
      </form>
    </div>

    <div class="comments_list">
    <?php

    // PHP Loding comments
    $get_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' AND removed='no' ORDER BY id DESC");
    $count = mysqli_num_rows($get_comments);

    if ($count != 0) {

      while($comment = mysqli_fetch_array($get_comments)){
        $comment_id = $comment['id'];
        $comment_body = $comment['comment_body'];
        $posted_by = $comment['posted_by'];
        $date_added = $comment['date_added'];

        $userDetailsQry = mysqli_query($con, "SELECT * FROM users WHERE username='$posted_by'");
        $userDetails = mysqli_fetch_array($userDetailsQry);
        $userPosition = $userDetails['position'];
        //posix_times
        $date_time_now = date("Y-m-d H:i:s");
        $start_date = new DateTime($date_added);
        $end_date = new DateTime($date_time_now);
        $interval = $start_date->diff($end_date);
        // if longer than one year
        if($interval->y >= 1) {
          if($interval == 1)
            $time_message = $interval->y . " year ago"; //1 year ago
          else
            $time_message = $interval->y . " years ago"; //1+ year ago
        }
        else if ($interval-> m >= 1) {
          if($interval->d == 0) {
            $days = " ago";
          }
          else if($interval->d == 1) {
            $days = $interval->d . " day ago";
          }
          else {
            $days = $interval->d . " days ago";
          }

          if($interval->m == 1) {
            $time_message = $interval->m . " month". $days;
          }
          else {
            $time_message = $interval->m . " months". $days;
          }

        }
        else if($interval->d >= 1) {
          if($interval->d == 1) {
            $time_message = "Yesterday";
          }
          else {
            $time_message = $interval->d . " days ago";
          }
        }
        else if($interval->h >= 1) {
          if($interval->h == 1) {
            $time_message = $interval->h . " hour ago";
          }
          else {
            $time_message = $interval->h . " hours ago";
          }
        }
        else if($interval->i >= 1) {
          if($interval->i == 1) {
            $time_message = $interval->i . " minute ago";
          }
          else {
            $time_message = $interval->i . " minutes ago";
          }
        }
        else {
          if($interval->s < 30) {
            $time_message = "Now";
          }
          else {
            $time_message = $interval->s . " seconds ago";
          }
        }

        $user_obj = new User($con, $posted_by);

        if ($userLoggedIn == $posted_by) {
          $comment_delete_button = "<span class='delete_button' id='comment$comment_id' style='color:#df7c7c; margin-left:30px; font-size: 10px;' onclick='delete_comment()'>Delete</span>";
        }
        else{
          $comment_delete_button = "";
        }

        ?>

        <!-- HTML - comment section -->
        <div class="comment_section">
          <a href="<?php echo $posted_by; ?>" target="_parent" style="line-height: 80%;"><img src="<?php echo $user_obj->getProfilePic();?>" title="<?php echo $posted_by; ?>" style="float:left;" height="30"></a>
          <a href="<?php echo $posted_by; ?>" target="_parent"><?php echo $user_obj->getFirstAndLastName();?></a>
          &nbsp;&nbsp;&nbsp;&nbsp; <?php echo "<small>" . $time_message . "</small>"?>
          <div style="float:right; margin-right:10px; cursor: pointer;"><?php echo $comment_delete_button; ?></div>
          <div style="color: #ACACAC; font-size:10px;"><?php echo $userPosition; ?></div>
          <div style="margin-left: 43px; margin-top:2px;"><?php echo $comment_body; ?></div>
          <hr style="margin: 10px 0 10px 0;">
        </div>

        <script>
          function delete_comment() {
            bootbox.confirm("Are you sure you want to delete this comment?", function(result) {
              $.post("includes/form_handlers/delete_comment.php?comment_id=<?php echo $comment_id; ?>", {result:result});
              if(result)
              window.parent.document.getElementById("comment_text<?php echo $post_id; ?>").textContent =
                "Comments(".concat(<?php echo $count - 1; ?>,")",String.fromCharCode(160),String.fromCharCode(160),String.fromCharCode(160),String.fromCharCode(160),String.fromCharCode(160),String.fromCharCode(160));

                window.top.location.reload();
            });
          }
        </script>

        <?php
        //echo "<a name='end' id='end'></a>";
      } //while loop ends here
    }
    else {
      echo "<center style='font-size: 12px;'>You can be the first one who comment this post!</center>";
    } //if ends here
    ?>
  </div>

  <script>

    function update_comments_count() {
      if (document.getElementById("comment_body<?php echo $post_id; ?>").value != ""){
        window.parent.document.getElementById("comment_text<?php echo $post_id; ?>").textContent =
        "Comments(".concat(<?php echo $count + 1; ?>,")",String.fromCharCode(160),String.fromCharCode(160),String.fromCharCode(160),String.fromCharCode(160),String.fromCharCode(160),String.fromCharCode(160));
      }
      else {
        alert("Enter a comment.")
      }
    }

  </script>


  </body>
</html>
