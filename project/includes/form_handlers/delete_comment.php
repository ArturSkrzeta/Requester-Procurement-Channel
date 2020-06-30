<?php

require '../../config/config.php';

echo '<script language="javascript">';
echo 'alert("message successfully sent")';
echo '</script>';

if (isset($_GET['comment_id']))
  $comment_id = $_GET['comment_id'];

  // echo "xxx";
  // $user_name_data = mysqli_query($con, "SELECT added_by FROM posts WHERE id='$post_id'");
  // $user_name_data_arr = mysqli_fetch_array($user_name_data);
  // $user_name = $user_name_data_arr['added_by'];
  //
  // $user_data = mysqli_query($con, "SELECT * FROM users WHERE username='$user_name'");
  // $user_data_arr = mysqli_fetch_array($user_data);
  // $current_num_posts = $user_data_arr['num_posts'];
  // $current_num_posts--;
  //
  // $query_update_num_posts = mysqli_query($con, "UPDATE users SET num_posts='$current_num_posts' WHERE username='$user_name'");

  if (isset($_POST['result'])) {
    if ($_POST['result'] == 'true')
      $query_update_deleted = mysqli_query($con, "UPDATE comments SET removed='yes' WHERE id='$comment_id'");
  }

?>
