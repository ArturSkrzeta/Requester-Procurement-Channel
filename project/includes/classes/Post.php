<?php

class Post {

  private $con;
  private $user_obj;


  public function __construct($con, $user) {
    $this->con = $con;
    $this->user_obj = new User($con, $user);
  }

  public function submitPost($body, $user_to) {
    $body = strip_tags($body);
    $body = mysqli_real_escape_string($this->con, $body); //ignoring single quotes
    $check_empty = preg_replace('/\s+/', '',$body); // deleting all spaces

    if ($check_empty != ""){
      //Get Date
      $date_added = date("Y-m-d H:i:s");
      //Get Author
      $added_by = $this->user_obj->getUsername(); // taking function from a class of the object
      //When user on o profile = user_to is 'none'
      if ($user_to == $added_by) {
        $user_to = "none";
      }
      //Insert Post
      // UPDATE EVERY TIME YOU CHANGE THE TABLE
      $query = mysqli_query($this->con,"INSERT INTO posts VALUE('','$body','$added_by', '$user_to', '$date_added','no', '0', 'no','0')");
      $returned_id = mysqli_insert_id($this->con);
      //Inset Notification

      //Update post count for users
      $num_posts = $this->user_obj->getNumPosts();
      $num_posts++;
      $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
    }
  }

  public function loadPosts($filtered, $user){

    $userLoggedIn = $this->user_obj->getUsername();

    // String for further appending
    $str = "";
    //Gettig all posts
    if ($filtered == False) {
      $data = mysqli_query($this->con,"SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");
    }
    else{
      $data = mysqli_query($this->con,"SELECT * FROM posts WHERE deleted='no' AND added_by='$user' ORDER BY id DESC");
    }

    while ($row = mysqli_fetch_array($data)) {
      $id = $row['id'];
      ?>

      <script>
        function toggle<?php echo $id; ?>() {

          var target = $(event.target);
          if (!target.is("a") && !target.is("img") && !target.is("span")) {
            var element = document.getElementById("toggleComment<?php echo $id; ?>");
            if (element.style.display == "block")
              element.style.display = "none";
            else
              element.style.display = "block";
          }
        }
      </script>

      <?php

      $comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id' and removed='no'");
      $comments_check_num = mysqli_num_rows($comments_check);
      $num_comments_query = mysqli_query($this->con, "UPDATE posts SET num_comments='$comments_check_num' WHERE id='$id'");

      $body = $row['body'];
      $added_by = $row['added_by'];
      $date_time = $row['date_added'];

      // if user that posted has its account closed
      $added_by_obj = new User($this->con, $added_by);
      if ($added_by_obj->isClosed()) {
        continue; // moving to next iteration witshout executing below code for current iteration
      }

      if ($userLoggedIn == $added_by) {
        $delete_button = "<span class='delete_button' id='post$id' style='color:#df7c7c; margin-left:30px; font-size: 12px;'>Delete</span>";
      }
      else{
        $delete_button = "";
      }

      $user_details_query = mysqli_query($this->con,"SELECT first_name, last_name, profile_pic, position FROM users WHERE username='$added_by'");
      $user_row = mysqli_fetch_array($user_details_query);

      //Post details
      $first_name = $user_row['first_name'];
      $last_name = $user_row['last_name'];
      $userPosition = $user_row['position'];
      $profile_pic = $user_row['profile_pic'];
      //Time frame
      $date_time_now = date("Y-m-d H:i:s");
      $start_date = new DateTime($date_time);
      $end_date = new DateTime($date_time_now);
      $interval = $start_date->diff($end_date);
      // if longer than one year ect.
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

      //Appending to string
      $str .= "
              <div class='status_post' onclick='javascript:toggle$id()'>
                <div class='post_profile_pic'><a href='$added_by'><img src='$profile_pic' width='50'></a></div>
                <div class='posted_by' style='color:#ACACAC;'><a href='$added_by'>$first_name $last_name</a>&nbsp;&nbsp;&nbsp;&nbsp;<small style='font-size:10px;'>$time_message</small></div>
                <small class='position' style='color:#ACACAC; margin-bottom:5px; font-size:10px;'>$userPosition</small>
                <div id='post_body'>$body<br></div>
                <div class='postOptions' style='color:#ACACAC; margin-left:85px; margin-top:10px;'><small id='comment_text$id'>Comments($comments_check_num)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small><small style='color:#058fc3;'>Add comment</small>$delete_button</div>
              </div> " .

              ($filtered == False ?
                "
                <div class='post_comment' id='toggleComment$id' style='display:none;'>
                  <iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0' style='height: 180px; width: 78%; margin-left: 85px; margin-bottom: 15px; margin-top: 5px; border-radius: 10px;'></iframe>
                </div>"
                :
                "
                <div class='post_comment' id='toggleComment$id' style='display:none;'>
                  <iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0' style='height: 180px; width: 80%; margin-left: 85px; margin-bottom: 15px; margin-top: 5px; border-radius: 10px;'></iframe>
                </div>"
              )

              . "<hr>";

              ?>

              <script>
      					$(document).ready(function() {
      						$('#post<?php echo $id; ?>').on('click', function() {
      							bootbox.confirm("Are you sure you want to delete this post?", function(result) {
      								$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});
      								if(result)
      									location.href = 'confirmation.php';
      							});
      						});
      					});
      				</script>

              <?php

    } // while loop ends here

    $str .= "<div class='end' style='width:100%; height:50px; font-size:14px; color:#ACACAC; margin: 10px 0 0 5px;'>No more posts to display...</div>";
    echo $str;

  } // function ends here
} // class ends here

?>
