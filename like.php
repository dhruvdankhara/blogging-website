<?php
include "./connection.php";
session_start();

$user_id = $_SESSION["user_id"];
$post_id = $_POST["post_id"];

$q = "select * from likes where post_id = $post_id and user_id =$user_id ";
$rq = mysqli_query($conn, $q);
if (mysqli_num_rows($rq) > 0) {
  $q = "delete from likes where post_id = $post_id and user_id =$user_id ";
  $rq = mysqli_query($conn, $q);
  if ($rq) {
    $q = "select * from likes where post_id = $post_id";
    $rq = mysqli_query($conn, $q);
    if ($rq) {
      $like_count = mysqli_num_rows($rq);
      $q = "UPDATE blog_posts SET like_count=$like_count WHERE post_id=$post_id";
      $rq = mysqli_query($conn, $q);
      if ($rq) {
        echo $like_count;
      }
    }
  }
} else {
  $q = "INSERT INTO likes(post_id,user_id) VALUES($post_id,$user_id)";
  $rq = mysqli_query($conn, $q);
  if ($rq) {
    $q = "select * from likes where post_id = $post_id";
    $rq = mysqli_query($conn, $q);
    if ($rq) {
      $like_count = mysqli_num_rows($rq);
      $q = "UPDATE blog_posts SET like_count=$like_count WHERE post_id=$post_id";
      $rq = mysqli_query($conn, $q);
      if ($rq) {
        echo $like_count;
      }
    }
    // if ($rq) {
    //   header("location:dashboard.php");
    // } else {
    //   header("location:dashboard.php");
    // }
  }
}
