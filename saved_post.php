<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogSphere</title>
  <link href="./lib/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./vendor/css/style.css">
  <link rel="stylesheet" href="./vendor/css/theme.css">
  <link rel="stylesheet" href="./vendor/css/profile.css">
  <link rel="icon" href="./assets/website_logo-removebg-preview.png" type="image/png">
</head>

<body>
  <?php
  require "./navbar_dash.php";
  ?>

  <?php
  if (isset($_POST["delete"]) && isset($_POST["save_id"])) {
    // echo "submitted!!";
    $save_id = $_POST["save_id"];

    $q = "DELETE FROM saved_posts WHERE save_id=$save_id";
    $rq = mysqli_query($conn, $q);

    if ($rq) {
      $message[] = array(
        'icon' => 'success',
        'type' => 'Saved Post Removed',
        'message' => 'Saved post removed successfully!'
      );
      $isSuccess = true;
    } else {
      $message[] = array(
        'icon' => 'error',
        'type' => 'Saved Post Removed',
        'message' => 'Error while deleting post!'
      );
      $isSuccess = false;
    }
  }
  ?>

  <?php

  $user_id = $_SESSION["user_id"];

  $q = "SELECT * FROM saved_posts WHERE user_id=$user_id";
  $rq = mysqli_query($conn, $q);

  if (mysqli_num_rows($rq) > 0) {
    $q = "SELECT *
    FROM blog_posts 
    JOIN saved_posts ON blog_posts.post_id = saved_posts.post_id
    WHERE saved_posts.user_id=$user_id";
    $fetchBlogRunQuery = mysqli_query($conn, $q);

  } else {
    $q = "SELECT *
    FROM blog_posts 
    JOIN saved_posts ON blog_posts.post_id = saved_posts.post_id
    WHERE saved_posts.user_id=$user_id";
    $fetchBlogRunQuery = mysqli_query($conn, $q);

  }
  include "./alert_message.php";

  ?>


  <div class="container my-4">
    <div class="row">
      <h2 class="col-sm-12 mb-4">Your saved blogs...</h2>
      <?php

      $count = mysqli_num_rows($fetchBlogRunQuery);
      // echo $count;
      if ($count == 0) {
        $msg = "Not found any saved Post!!";

        echo "
          <p class='text-danger fs-4'>$msg</p>
        ";
      } else {
        while ($result = mysqli_fetch_assoc($fetchBlogRunQuery)) {

          if ($result["comment_count"] == null) {
            $result["comment_count"] = 0;
          }

          $post_id = $result["post_id"];
          $save_id = $result["save_id"];
          ?>
          <div class="col-sm-6">
            <div class="card mb-3">
              <div class="row g-0">
                <div class="col-md-4">
                  <img src="<?= $result["image"] ?>" onerror="this.src='assets/website_logo-removebg-preview.png'"
                    width="100%" height="100%" class="img-fluid rounded-start" alt="...">
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">
                      <?= $result["title"] ?>
                    </h5>
                    <p class="card-text">
                      <i class="bi bi-heart-fill"></i> <span>
                        <?= $result["like_count"] ?>
                      </span>
                    </p>
                    <p class="card-text"><small class="text-body-secondary">
                        <?= $result["created_at"] ?>
                      </small>
                    </p>
                    <a href="single_post.php?post_id=<?= $post_id ?>">
                      <button class="btn btn-custom">
                        <i class="bi bi-eye fs-4"></i>
                      </button>
                    </a>
                    <form method="post" style="float:right;">
                      <input type="hidden" name="save_id" value="<?= $save_id ?>">
                      <button class="btn" type="submit" name="delete"
                        style="background: transparent; border: none; padding: 0;">
                        <i class="bi bi-bookmark-fill fs-4" style="color: #000000;"></i>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <?php
        }
      }

      ?>
    </div>
  </div>

</body>

</html>