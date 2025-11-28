<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>BlogSphere Admin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link rel="shortcut icon" href="../assets/website_logo-removebg-preview.png" type="image/x-icon">
  <link href="../lib/css/bootstrap.min.css" rel="stylesheet">
  <!-- Load theme last so it can override component styles -->
  <link href="../vendor/css/theme.css" rel="stylesheet">
  <link href="../lib/css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <script src="../lib/js/sweetalert2.all.js"></script>

</head>
<?php
include "../connection.php";
session_start();
if (!isset($_SESSION["user_id"])) {
  header("location:../index.php");
}


$user_id = $_SESSION["user_id"];

$query = "SELECT * FROM Users WHERE user_id='$user_id'";
$runquery = mysqli_query($conn, $query);


$row = mysqli_fetch_assoc($runquery);

$name = $row["name"];
$image = $row["image"];

if ($row['user_type'] == "client") {
  header("location:../dashboard.php");
}

?>


<body>

  <header class="header_nav">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-2">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
          <span class="fw-bold text-brand">Blog<span style="color: #2ecc71;">Sphere</span> <small
              class="text-muted">Admin</small></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
          aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pillbar">
            <li class="nav-item"><a
                class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>"
                href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a
                class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'userInfo.php' ? 'active' : '' ?>"
                href="userInfo.php">Users</a></li>
            <li class="nav-item"><a
                class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'blog_post.php' ? 'active' : '' ?>"
                href="blog_post.php">Posts</a></li>
            <li class="nav-item"><a
                class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'category.php' ? 'active' : '' ?>"
                href="category.php">Categories</a></li>
            <li class="nav-item"><a
                class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'promotion_package.php' ? 'active' : '' ?>"
                href="promotion_package.php">Packages</a></li>
            <li class="nav-item"><a
                class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'campaign.php' ? 'active' : '' ?>"
                href="campaign.php">Campaigns</a></li>
          </ul>

          <div class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" href="#" data-bs-toggle="dropdown" aria-expanded="false"
              aria-label="Open profile menu">
              <img src="<?= "." . $image ?>" onerror="this.src='../assets/default-profile.png'" alt="Profile"
                class="rounded-circle" width="32" height="32" style="object-fit:cover;">
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
              <li class="dropdown-header text-center">
                <h6 class="fw-bolder text-capitalize mb-0"><?= $name ?></h6>
                <small class="text-muted">Administrator</small>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="profile.php">
                  <i class="me-3 bi bi-person"></i>
                  <span>My Profile</span>
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center" href="../logout.php">
                  <i class="me-3 bi bi-box-arrow-right"></i>
                  <span>Sign Out</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <script src="../lib/js/main.js"></script>
  <script src="../lib/js/bootstrap.bundle.min.js"></script>

</body>

</html>