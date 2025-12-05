<?php
require_once("../connection.php");
include "./sidebar.php";
?>

<main id="main" class="main">
  <section class="section dashboard m-4">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-white">
            <h3>Post Information</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr class="text-center">
                    <th scope="col">Sr No.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Content</th>
                    <th scope="col">Like</th>
                    <th scope="col">Category</th>
                    <th scope="col">Image</th>
                  </tr>
                </thead>

                <tbody class="text-center">
                  <?php
                  $query = "SELECT * FROM blog_posts";
                  $result = mysqli_query($conn, $query);
                  $i = 1;
                  while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                      <th scope='row'>
                        <?= $i ?>
                      </th>
                      <td>
                        <?= $row["title"] ?>
                      </td>
                      <td>
                        <?= $row["content"] ?>
                      </td>
                      <td>
                        <?= $row["like_count"] ?>
                      </td>
                      <td>
                        <?php
                        $category = "SELECT * FROM categories where category_id =  $row[category_id]";
                        $categoryData = mysqli_query($conn, $category);
                        $categoryName = mysqli_fetch_assoc($categoryData);
                        echo ($categoryName["name"]);
                        ?>
                      </td>
                      <td>
                        <img class="rounded-3 border" height="60" width="60" src="<?= "." . $row["image"] ?>"
                          alt="Post image">
                      </td>
                    </tr>

                    <?php
                    $i++;
                  }
                  ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>