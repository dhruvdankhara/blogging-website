<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogHive</title>
  <link href="./lib/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./vendor/swiper/swiper-bundle.min.css">
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

  <!-- Hero slider -->
  <section id="hero-slider" class="py-4">
    <div class="container-fluid px-3 px-md-4">
      <div class="row">
        <div class="col-12">
          <div class="swiper sliderFeaturedPosts card border-0 shadow-sm overflow-hidden rounded-3"
            style="background: transparent;">
            <div class="swiper-wrapper">
              <?php
              $q1 = "SELECT b.* FROM blog_posts b,campaigns c WHERE b.post_id=c.post_id AND NOW() BETWEEN c.start_date AND c.end_date ORDER BY c.total_amount DESC";
              $runq1 = mysqli_query($conn, $q1);

              $rowCount = mysqli_num_rows($runq1);

              if ($rowCount > 0) {
                while ($row = mysqli_fetch_assoc($runq1)) {
                  $image = $row["image"];
                  $title = $row["title"];
                  $content = $row["content"];
                  ?>

                  <div class="swiper-slide">
                    <a class="d-flex align-items-end p-3 p-md-4 text-white position-relative"
                      href="single_post.php?post_id=<?= $row['post_id'] ?>"
                      style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.6)), url('<?= file_exists($image) ? $image : 'assets/site_logo.jpg' ?>'); background-size: cover; background-position: center; min-height: 280px; height: 320px; border-radius: .5rem; text-decoration: none;">
                      <div class="position-relative z-1">
                        <h2 class="h4 h3-md mb-2 text-white">
                          <?= htmlspecialchars($title) ?>
                        </h2>
                        <p class="mb-0 d-none d-md-block text-white-50 small">
                          <?= htmlspecialchars(mb_strimwidth(strip_tags($content), 0, 100, '...')) ?>
                        </p>
                      </div>
                    </a>
                  </div>
                  <?php
                }
              } else {
                echo "
                  <div class='swiper-slide'>
          <a class='d-flex align-items-end p-4 text-white' style='min-height:320px; background: #1e293b; border-radius:.5rem;'>
                      <div>
                        <h2>
                          Promotion your post for display in main page!
                        </h2>
                        <p>
                          Go to the promotion page and add your favourite post to visible first in all over user's dashboard!
                        </p>
                      </div>
                    </a>
                  </div>
                ";
              }
              ?>
            </div>
            <div class="custom-swiper-button-next">
              <span class="bi-chevron-right text-white"></span>
            </div>
            <div class="custom-swiper-button-prev">
              <span class="bi-chevron-left text-white"></span>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="posts" class="posts py-4">
    <div class="container-fluid px-3 px-md-4" data-aos="fade-up">
      <div class="row g-3 g-md-4">
        <?php
        $query = "SELECT * FROM blog_posts ORDER BY created_at DESC";
        $runquery = mysqli_query($conn, $query);

        if (mysqli_num_rows($runquery) == 0) {
          echo "<div class='col-12 text-center py-5'>";
          echo "<i class='bi bi-search fs-1 text-muted'></i>";
          echo "<h4 class='mt-3 text-muted'>No posts found</h4>";
          echo "</div>";
        }

        while ($row = mysqli_fetch_assoc($runquery)) {
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

          ?>
          <div class="col-12 col-sm-6 col-lg-4 mb-4">
            <article class="card h-100 shadow-sm border-0" role="link" tabindex="0" style="cursor: pointer;"
              onclick="window.location.href='single_post.php?post_id=<?= $post_id ?>'"
              onkeydown="if(event.key==='Enter'){ window.location.href='single_post.php?post_id=<?= $post_id ?>'; }">
              <img class="card-img-top" src="<?= $image ?>" onerror="this.src='assets/site_logo.jpg'"
                alt="<?= htmlspecialchars($title) ?>" style="object-fit: cover; aspect-ratio: 16/9; height: 200px;">
              <div class="card-body p-3">
                <div class="d-flex flex-wrap align-items-center mb-2 gap-1">
                  <span class="badge text-bg-success flex-shrink-0"><?= htmlspecialchars($category) ?></span>
                  <small class="text-muted"><?= date('M j, Y', strtotime($created_at)) ?></small>
                </div>
                <h5 class="card-title mb-2 fs-6 fs-md-5"><?= htmlspecialchars($title) ?></h5>
                <p class="card-text text-muted mb-3 small">
                  <?= htmlspecialchars(mb_strimwidth(strip_tags($content), 0, 120, '...')) ?>
                </p>
                <div class="d-flex align-items-center flex-wrap gap-2">
                  <div class="d-flex align-items-center flex-grow-1">
                    <img class="rounded-circle me-2 flex-shrink-0" src="<?= $user["image"] ?>"
                      onerror="this.src='assets/default-profile.png'" alt="<?= htmlspecialchars($user["name"]) ?>"
                      width="28" height="28" style="object-fit: cover;">
                    <small class="text-muted text-truncate"><?= htmlspecialchars($user["name"]) ?></small>
                  </div>
                  <?php
                  $q = "select * from likes where post_id = $post_id and user_id =$loggedin_user ";
                  $rq = mysqli_query($conn, $q);
                  ?>
                  <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <div class="d-flex align-items-center">
                      <div id="icon_<?= $post_id ?>" onclick="event.stopPropagation(); likePost(<?= $post_id ?>)"
                        class="me-1" role="button" tabindex="0">
                        <i
                          class="bi <?= mysqli_num_rows($rq) == 0 ? 'bi-hand-thumbs-up' : 'bi-hand-thumbs-up-fill' ?> fs-6"></i>
                      </div>
                      <div id="likeCount_<?= $post_id ?>" class="small text-muted">
                        <?= $like_count ?>
                      </div>
                    </div>
                    <div class="small text-muted d-flex align-items-center">
                      <i class="bi bi-chat-dots me-1"></i><?= $comment_count ?>
                    </div>
                  </div>
                </div>
              </div>
            </article>
          </div>
          <?php
        }
        ?>

      </div>
    </div>
  </section>
</body>

<script src="./vendor/swiper/swiper-bundle.min.js"></script>
<script src="./vendor/js/main.js"></script>
<script src="vendor/js/ajex-call.js"></script>

</html>