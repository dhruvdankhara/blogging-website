<?php
include "./navbar_dash.php";

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
?>

<div class="container-fluid px-3 px-md-4 py-4">
  <?php if (!empty($search_query)): ?>
  </div>

  <div class="row g-3 g-md-4">
    <?php
    $search_query_safe = mysqli_real_escape_string($conn, $search_query);

    // Enhanced search query with JOIN for better results
    $query = "SELECT DISTINCT blog_posts.*, 
                       categories.name as category_name,
                       users.name as author_name,
                       users.image as author_image
                FROM blog_posts 
                LEFT JOIN categories ON blog_posts.category_id = categories.category_id
                LEFT JOIN users ON blog_posts.user_id = users.user_id
                WHERE blog_posts.title LIKE '%$search_query_safe%' 
                   OR blog_posts.content LIKE '%$search_query_safe%'
                   OR categories.name LIKE '%$search_query_safe%'
                   OR users.name LIKE '%$search_query_safe%'
                ORDER BY blog_posts.created_at DESC";

    $runquery = mysqli_query($conn, $query);
    $result_count = mysqli_num_rows($runquery);

    if ($result_count > 0): ?>

      <?php while ($row = mysqli_fetch_assoc($runquery)):
        $post_id = $row["post_id"];
        $title = $row["title"];
        $content = $row["content"];
        $category_name = $row["category_name"];
        $created_at = $row["created_at"];
        $image = $row["image"];
        $like_count = $row["like_count"] ?: 0;
        $comment_count = $row["comment_count"] ?: 0;
        $author_name = $row["author_name"];
        $author_image = $row["author_image"];

        // Highlight search terms in title and content
        $highlighted_title = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<span class="search-highlight">$1</span>', htmlspecialchars($title));
        $highlighted_content = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<span class="search-highlight">$1</span>', htmlspecialchars(mb_strimwidth(strip_tags($content), 0, 150, '...')));
        ?>
        <div class="col-12 col-sm-6 col-lg-4 mb-4">
          <article class="card h-100 shadow-sm border-0" role="link" tabindex="0" style="cursor: pointer;"
            onclick="window.location.href='single_post.php?post_id=<?= $post_id ?>'"
            onkeydown="if(event.key==='Enter'){ window.location.href='single_post.php?post_id=<?= $post_id ?>'; }">
            <img class="card-img-top" src="<?= $image ?>" onerror="this.src='assets/site_logo.jpg'"
              alt="<?= htmlspecialchars($title) ?>" style="object-fit: cover; aspect-ratio: 16/9; height: 200px;">
            <div class="card-body p-3">
              <div class="d-flex flex-wrap align-items-center mb-2 gap-1">
                <span class="badge text-bg-success flex-shrink-0"><?= htmlspecialchars($category_name) ?></span>
                <small class="text-muted"><?= date('M j, Y', strtotime($created_at)) ?></small>
              </div>
              <h5 class="card-title mb-2 fs-6 fs-md-5"><?= $highlighted_title ?></h5>
              <p class="card-text text-muted mb-3 small"><?= $highlighted_content ?></p>
              <div class="d-flex align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center flex-grow-1">
                  <img class="rounded-circle me-2 flex-shrink-0" src="<?= $author_image ?>"
                    onerror="this.src='assets/default-profile.png'" alt="<?= htmlspecialchars($author_name) ?>" width="28"
                    height="28" style="object-fit: cover;">
                  <small class="text-muted text-truncate"><?= htmlspecialchars($author_name) ?></small>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                  <div class="d-flex align-items-center">
                    <i class="bi bi-hand-thumbs-up me-1"></i>
                    <small class="text-muted"><?= $like_count ?></small>
                  </div>
                  <div class="small text-muted d-flex align-items-center">
                    <i class="bi bi-chat-dots me-1"></i><?= $comment_count ?>
                  </div>
                </div>
              </div>
            </div>
          </article>
        </div>
      <?php endwhile; ?>

    <?php else: ?>
      <div class="col-12">
        <div class="search-no-results">
          <i class="bi bi-search text-muted"></i>
          <h4 class="mt-3 text-muted">No results found</h4>
          <p class="text-muted mb-4">We couldn't find any posts matching "<?= htmlspecialchars($search_query) ?>"</p>
          <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
            <a href="dashboard.php" class="btn btn-primary">
              <i class="bi bi-house me-1"></i>Go to Home
            </a>
            <a href="add_blog.php" class="btn btn-outline-primary">
              <i class="bi bi-plus me-1"></i>Create New Post
            </a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

<?php else: ?>
  <div class="search-no-results">
    <i class="bi bi-search text-muted"></i>
    <h4 class="mt-3 text-muted">Enter a search term</h4>
    <p class="text-muted mb-4">Use the search box above to find posts, categories, or authors</p>
    <a href="dashboard.php" class="btn btn-primary">
      <i class="bi bi-house me-1"></i>Go to Home
    </a>
  </div>
<?php endif; ?>
</div>

<script src="./vendor/swiper/swiper-bundle.min.js"></script>
<script src="./vendor/js/main.js"></script>
<script src="vendor/js/ajex-call.js"></script>