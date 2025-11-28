<?php
include "./sidebar.php";
?>

<main class="container-fluid px-3 px-md-4 py-4">
  <div
    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <h1 class="h4 h3-md mb-0 text-gray-800">Admin Dashboard</h1>
  </div>

  <div class="row g-3 g-md-4 mb-4">

    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div
                class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
                style="width: 40px; height: 40px;">
                <i class="bi bi-people fs-6"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-2 ms-md-3">
              <div class="small text-muted">Total Users</div>
              <div class="h5 h4-md mb-0">
                <?php
                $query = "select * from Users";
                $result = mysqli_query($conn, $query);
                $rowCount = mysqli_num_rows($result);
                echo ($rowCount);
                ?>
              </div>
            </div>
            <div class="flex-shrink-0 d-none d-md-block">
              <a href="./userInfo.php" class="btn btn-sm btn-outline-primary">View</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div
                class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success"
                style="width: 40px; height: 40px;">
                <i class="bi bi-boxes fs-6"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-2 ms-md-3">
              <div class="small text-muted">Categories</div>
              <div class="h5 h4-md mb-0">
                <?php
                $query = "select * from categories";
                $result = mysqli_query($conn, $query);
                $rowCount = mysqli_num_rows($result);
                echo ($rowCount);
                ?>
              </div>
            </div>
            <div class="flex-shrink-0 d-none d-md-block">
              <a href="./category.php" class="btn btn-sm btn-outline-success">View</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div
                class="rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info"
                style="width: 40px; height: 40px;">
                <i class="bi bi-postcard-heart-fill fs-6"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-2 ms-md-3">
              <div class="small text-muted">Total Posts</div>
              <div class="h5 h4-md mb-0">
                <?php
                $query = "select * from blog_posts";
                $result = mysqli_query($conn, $query);
                $rowCount = mysqli_num_rows($result);
                echo ($rowCount);
                ?>
              </div>
            </div>
            <div class="flex-shrink-0 d-none d-md-block">
              <a href="./blog_post.php" class="btn btn-sm btn-outline-info">View</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div
                class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning"
                style="width: 40px; height: 40px;">
                <i class="bi bi-award fs-6"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-2 ms-md-3">
              <div class="small text-muted">Packages</div>
              <div class="h5 h4-md mb-0">
                <?php
                $query = "select * from campaign_package";
                $result = mysqli_query($conn, $query);
                $rowCount = mysqli_num_rows($result);
                echo ($rowCount);
                ?>
              </div>
            </div>
            <div class="flex-shrink-0 d-none d-md-block">
              <a href="./promotion_package.php" class="btn btn-sm btn-outline-warning">View</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Second Row -->
  <div class="row g-3 g-md-4">
    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-3 p-md-4">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div
                class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success"
                style="width: 48px; height: 48px;">
                <i class="bi bi-bar-chart-line fs-5"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <div class="small text-muted">Running Campaigns</div>
              <div class="h4 mb-0">
                <?php
                $query = "select * from campaigns where status = 'running'";
                $result = mysqli_query($conn, $query);
                $rowCount = mysqli_num_rows($result);
                echo ($rowCount);
                ?>
              </div>
            </div>
            <div class="flex-shrink-0 d-none d-md-block">
              <a href="./campaign.php" class="btn btn-sm btn-outline-success">View</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-3 p-md-4">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
              <div
                class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
                style="width: 40px; height: 40px;">
                <i class="bi bi-currency-rupee fs-6"></i>
              </div>
            </div>
            <div class="flex-grow-1 ms-2 ms-md-3">
              <div class="small text-muted">Total Revenue</div>
              <div class="h5 h4-md mb-0">₹
                <?php
                $query = "select sum(total_amount) from campaigns";
                $result = mysqli_query($conn, $query);
                $rowCount = mysqli_fetch_row($result)[0];
                echo ($rowCount ?: '0');
                ?>
              </div>
            </div>
            <div class="flex-shrink-0 d-none d-md-block">
              <a href="./campaign.php" class="btn btn-sm btn-outline-primary">View</a>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</main>