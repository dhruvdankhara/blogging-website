<?php
include "connection.php";
?>

<?php
if (isset($_GET["commentid"]) && isset($_GET["post_id"])) {
  $commentid = $_GET["commentid"];
  $post_id = $_GET["post_id"];

  // echo $commentid;

  $q = $conn->query("DELETE FROM comments WHERE comment_id=$commentid");
  // print_r($q);

  if ($q) {
    // echo $q;
    header("location:single_post.php?post_id=$post_id");
  } else {
    // echo $q;
    header("location:single_post.php?post_id=$post_id");
  }

} else {
  header("location:dashboard.php");
}
?>