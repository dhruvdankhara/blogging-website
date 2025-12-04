<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogHive</title>
  <link href="./lib/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./vendor/css/style.css">
  <link rel="stylesheet" href="./vendor/css/theme.css">
  <link rel="stylesheet" href="./vendor/css/single_post.css">
  <link rel="icon" href="./assets/website_logo-removebg-preview.png" type="image/png">

</head>

<body>
  <?php
  include "./navbar_dash.php";
  ?>

  <?php
  if (!isset($_SESSION["user_id"])) {
    header("location:./index.php");
  }

  $loggedin_user = $_SESSION["user_id"];
  ?>

  <?php

  if (isset($_GET["post_id"])) {
    $post_id = $_GET["post_id"];

    $query = "SELECT * FROM blog_posts WHERE post_id=$post_id";
    $runquery = mysqli_query($conn, $query);

    if (mysqli_num_rows($runquery) == 1) {
      $row = mysqli_fetch_assoc($runquery);
      $user_id = $row["user_id"];
      $post_id = $row["post_id"];
      $title = $row["title"];
      $content = $row["content"];
      $category = $row["category_id"];

      $category = "SELECT * FROM categories where category_id =  $row[category_id]";
      $categoryData = mysqli_query($conn, $category);
      $categoryName = mysqli_fetch_assoc($categoryData);
      $category = $categoryName["name"];
      $created_at = $row["created_at"];
      $image = $row["image"];
      $like_count = $row["like_count"];

      if ($like_count == null) {
        $like_count = 0;
      }

      $comment_count = $row["comment_count"];

      if ($comment_count == null) {
        $comment_count = 0;
      }

      $query1 = "select * from users where user_id='$user_id'";
      $runquery1 = mysqli_query($conn, $query1);

      $user = mysqli_fetch_assoc($runquery1);
    }
  } else {
    include "page-not-found.php";
    return;
  }

  if (isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    $loggedin_user = $_SESSION["user_id"];
    $post_id = $_GET["post_id"];

    // Insert data into the comments table
    $sql = "INSERT INTO comments (	post_id	, 	user_id	, content, created_at	) VALUES ($post_id, $loggedin_user, '$comment', NOW())";

    if ($conn->query($sql) === TRUE) {
      $message[] = array(
        'icon' => 'success',
        'type' => 'Success',
        'message' => 'Comment submitted successfully!'
      );
    } else {
      $message[] = array(
        'icon' => 'success',
        'type' => 'Success',
        'message' => "Error: " . $sql . "<br>" . $conn->error
      );

    }
  }

  if (isset($_POST["save"]) && isset($_POST["post_id"])) {

    $post_id = $_POST["post_id"];
    $login_id = $_SESSION["user_id"];

    $q = "SELECT * FROM saved_posts WHERE post_id=$post_id AND user_id=$login_id";
    $rq = mysqli_query($conn, $q);

    if (mysqli_num_rows($rq) > 0) {
      $message[] = array(
        'icon' => 'error',
        'type' => 'Error',
        'message' => 'Post already saved!'
      );
    } else {
      $q = "INSERT INTO saved_posts(post_id,user_id) VALUES($post_id,$login_id)";

      $rq = mysqli_query($conn, $q);

      if ($rq) {
        $message[] = array(
          'icon' => 'success',
          'type' => 'Success',
          'message' => 'Post saved successfully!'
        );
      } else {
        $message[] = array(
          'icon' => 'error',
          'type' => 'Error',
          'message' => 'Unable to save post!'
        );
      }
    }
  }

  if (isset($_POST["unsave"]) && isset($_POST["post_id"])) {
    // echo "unsave submitted";
    $post_id = $_POST["post_id"];
    $login_id = $_SESSION["user_id"];

    $q = "DELETE FROM saved_posts WHERE post_id=$post_id AND user_id=$login_id";
    // echo $q;
    $rq = mysqli_query($conn, $q);

    if ($rq) {
      $message[] = array(
        'icon' => 'success',
        'type' => 'Success',
        'message' => 'Post unsaved successfully!'
      );
    } else {
      $message[] = array(
        'icon' => 'error',
        'type' => 'Error',
        'message' => 'Unable to unsave post!'
      );
    }
  }

  include "./alert_message.php";

  ?>
  <link rel="stylesheet" href="vendor\css\comment.css">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="card-2">
          <div class="thumbnail">
            <img class="left" src="<?= $image ?>" onerror="this.src='assets/website_logo-removebg-preview.png'" />
          </div>
        </div>
        <div class="right">
          <h1>
            <?= $title ?>
          </h1>

          <div class="separator"></div>
          <p>
            <?= $content ?>
          </p>
          <div class="author mb-4">
            <div>
              <img src="<?= $user["image"] ?>" onerror="this.src='assets/default-profile.png'" />
              <h5 class="ms-2 mb-0 mt-3">
                <?= $user["name"] ?>
              </h5>
            </div>
            <div>
              <div class="d-flex align-items-center lc_icons ms-auto">
                <?php
                $q = "select * from likes where post_id = $post_id and user_id =$loggedin_user ";
                $rq = mysqli_query($conn, $q);
                ?>

                <div id="icon_<?= $post_id ?>" onclick="likePost(<?= $post_id ?>)">
                  <i
                    class="bi <?= mysqli_num_rows($rq) == 0 ? 'bi-hand-thumbs-up' : 'bi-hand-thumbs-up-fill' ?> fs-3"></i>&nbsp;
                </div>

                <div id="likeCount_<?= $post_id ?>" class="me-2">
                  <?= $like_count ?>
                </div>

                <?php
                // Check if post is already saved
                $saved_check = "SELECT * FROM saved_posts WHERE post_id=$post_id AND user_id=$loggedin_user";
                $saved_result = mysqli_query($conn, $saved_check);
                $is_saved = mysqli_num_rows($saved_result) > 0;

                ?>

                <form action="" method="POST">
                  <input type="hidden" name="post_id" value="<?= $post_id ?>">
                  <?php if ($is_saved) { ?>
                    <button class="btn btn-sm ms-2" type="submit" name="unsave"
                      style="color: #94cfc4; background-color: #3c463d;">
                      <i class="las la-bookmark fs-3"></i>&nbsp;
                    </button>

                  <?php } else { ?>
                    <button class="btn btn-sm btn-custom ms-2" type="submit" name="save">
                      <i class="lar la-bookmark fs-3"></i>&nbsp;
                    </button>
                  <?php } ?>

                </form>
                <?php

                $comment_count = get_comment_count($post_id, $conn);

                function get_comment_count($post_id, $conn)
                {
                  $result = $conn->query("SELECT COUNT(*) FROM comments WHERE post_id = $post_id");
                  $count = $result->fetch_row()[0];
                  return $count;
                }
                ?>

                <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal"
                  data-bs-target="#exampleModal">
                  <i class="bi bi-chat-dots-fill"></i> <span class="badge bg-secondary">
                    <?= $comment_count ?>
                  </span>
                </button>

                <!-- Modal -->
                <div class="modal modal-lg fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                  aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                          <?= $title ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">

                        <form action="single_post.php?post_id=<?= $post_id ?>" class="comment-from" method="post">

                          Comment: <textarea name="comment" class="comment-enter-details"></textarea><br>
                          <input type="submit" value="Submit" class="btn btn-custom">
                        </form>
                        <?php
                        $sql = "SELECT *
                        FROM comments
                        JOIN users ON comments.user_id = users.user_id
                        WHERE comments.post_id = $post_id";

                        $result = $conn->query($sql);

                        if ($result === false) {
                          die('Error executing query: ' . $conn->error);
                        }

                        if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="comment my-2">
                              <div class="user-banner">
                                <div class="user">
                                  <div class="avatar">
                                    <img src="<?= $row["image"] ?>">
                                    <span class="stat grey"></span>
                                  </div>
                                  <h5 class="mx-2">
                                    <?= $row["name"] ?>
                                  </h5>
                                </div>
                                <button class="btn dropdown"><i class="ri-more-line"></i></button>
                                <?php if ($row["user_id"] == $_SESSION["user_id"]): ?>
                                  <button class="btn btn-danger delete-comment" data-comment-id="<?= $row['comment_id'] ?>">
                                    <a
                                      href="delete_comment.php?commentid=<?= $row['comment_id'] ?>&post_id=<?= $post_id ?>">Delete</a></button>
                                <?php endif; ?>
                              </div>
                              <div class="content p-0">
                                <p>
                                  <?= $row["content"] ?>
                                </p>
                                <span class="comment-time">
                                  <?= date('F j, Y, g:i a', strtotime($row["created_at"])) ?>
                                </span>
                              </div>
                            </div>
                            <?php

                          }
                        } else {
                          echo "No comments yet";
                        }

                        ?>
                      </div>

                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  </div>


</body>

<script src="vendor/js/ajex-call.js"></script>
<script>
  function comment() {
    $.ajax({
      url: "comment-add.php",
      success: function (result) {
        $("#div1").html(result);
      }
    });
  }
</script>

</html>