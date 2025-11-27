<?php
require_once("../connection.php");
include "./sidebar.php";
if (isset($_POST["submit"]) && isset($_POST["title"])  && isset($_POST["post_id"]) && isset($_POST["content"])  && isset($_POST["category"])) {

  $post_id = $_POST["post_id"];
  $title = $_POST["title"];
  $category = $_POST["category"];
  $content = $_POST["content"];
  if (
    $post_id &&
    $title &&
    $category &&
    $content
  ) {
    if ($_FILES["image"]["name"]) {
      $filename = $_FILES["image"]["name"];
      $tempname = $_FILES["image"]["tmp_name"];
      $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
      $folder = "./upload/post/" . time() . "." . $file_extension;
      $allowed_image_extension = array(
        "png",
        "jpg",
        "jpeg"
      );
      $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
      $query = "select * from  blog_posts where post_id = '$post_id' ";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      if (!in_array($file_extension, $allowed_image_extension)) {
        $message[] = array(
          'icon' => 'error',
          'type' => 'Error',
          'message' => 'Upload valid images. Only PNG and JPEG are allowed.'
        );
      } else if (move_uploaded_file($tempname, "." . $folder)) {
        $content = str_replace("'", "", $content);
        $query = "update  blog_posts set  title = '" . $title . "' ,content = '" . $content . "' ,category_id = '$category' , image = '$folder'    where post_id = '$post_id'";
        $runquery = mysqli_query($conn, $query);
        if ($runquery) {
          $message[] = array(
            'type' => 'Update',
            'message' => 'Blog Post Update successfully!',
            'icon' => 'success'
          );
          if ($row['image']) {
            $filenameRm = "." . $row['image'];
            if (file_exists($filenameRm)) {
              $status = unlink($filenameRm) ? 'The file ' . $filenameRm . ' has been deleted' : 'Error deleting ' . $filenameRm;
              // echo $status;
            }
          }
        }
      }
    } else {
      $content = str_replace("'", "", $content);
      $query = "update  blog_posts set  title = '$title' ,content = '$content' ,category_id = '$category'     where post_id = '$post_id'";
      $runquery = mysqli_query($conn, $query);
      if ($runquery) {
        $message[] = array(
          'type' => 'Update',
          'message' => 'Blog Post Update successfully!',
          'icon' => 'success'
        );
      }
    }
  } else {
    $message[] = array(
      'icon' => 'error',
      'type' => 'Error',
      'message' => 'Enter  valid  Form Information'
    );
  }
}
if (isset($_POST["insert"]) && isset($_POST["title"]) && isset($_POST["content"]) && isset($_POST["category"])) {

  $title = $_POST["title"];
  $category = $_POST["category"];
  $content = $_POST["content"];
  if (
    $title &&
    $category &&
    $content &&
    $_FILES["image"]["name"]
  ) {
    $filename = $_FILES["image"]["name"];
    $tempname = $_FILES["image"]["tmp_name"];
    $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $folder = "./upload/post/" . time() . "." . $file_extension;
    $allowed_image_extension = array(
      "png",
      "jpg",
      "jpeg"
    );
    if (!in_array($file_extension, $allowed_image_extension)) {
      $message[] = array(
        'icon' => 'error',
        'type' => 'Error',
        'message' => 'Upload valid images. Only PNG and JPEG are allowed.'
      );
    } else if (move_uploaded_file($tempname, "." . $folder)) {
      $user_id = $_SESSION["user_id"];
      $query = "insert into  blog_posts ( user_id, title , content ,category_id,image) values  ( '$user_id','$title' , '$content','$category','$folder')";
      $runquery = mysqli_query($conn, $query);
      if ($runquery) {
        $message[] = array(
          'type' => 'Add Blog Post',
          'message' => 'Blog Post Insert successfully!',
          'icon' => 'success'
        );
      }
    }
  } else {
    $message[] = array(
      'icon' => 'error',
      'type' => 'Error',
      'message' => 'Enter  valid  Form Information'
    );
  }
}
if (isset($_POST["delete"]) && isset($_POST["post_id"])) {
  $post_id = $_POST["post_id"];
  $query = "select * from  blog_posts where post_id = '$post_id' ";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  if ($row['image']) {
    $filenameRm = "." . $row['image'];
    if (file_exists($filenameRm)) {
      $status = unlink($filenameRm) ? 'The file ' . $filenameRm . ' has been deleted' : 'Error deleting ' . $filenameRm;
      // echo $status;
    }
  }
  // Delete associated likes first
  $query = "delete from likes where post_id = '$post_id'";
  $runquery = mysqli_query($conn, $query);
  
  // Delete associated comments first
  $query = "delete from comments where post_id = '$post_id'";
  $runquery = mysqli_query($conn, $query);
  
  // Now delete the blog post
  if ($runquery) {
    $query = "delete from blog_posts where post_id = '$post_id'";
    $runquery = mysqli_query($conn, $query);
    if ($runquery) {
      $message[] = array(
        'icon' => 'success',
        'type' => 'Blog Post Delete',
        'message' => 'Blog Post delete successfully!',
      );
    }
  }
}

include "../alert_message.php";

?>

<main id="main" class="main">
  <section class="section dashboard">
    <div class="row">
      <div class="card">
        <div class="card-body">
          <h3 class="my-3 float-start">Post Information</h3>
          <div class="btn btn-primary float-end" data-bs-toggle='modal' data-bs-target='#edit-category-modal'>Add Post
          </div>
          <!-- Table with stripped rows -->
          <table class="table table-striped text-center">
            <thead>
              <tr>
                <th scope="col">Sr No.</th>
                <th scope="col">Title</th>
                <th scope="col">Content</th>
                <th scope="col">Like</th>
                <th scope="col">Category</th>
                <th scope="col">Image</th>
                <th scope="col" style="width:200px">Action</th>
              </tr>
            </thead>

            <tbody>
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
                  <td><img class="rounded-5" height="70px" width="70px" src="<?= "." . $row["image"] ?>" alt=""></td>

                  <td>

                    <form method="post" id='edit-form' class="m-0">
                      <input class='form-control' type='hidden' value="<?= $row["post_id"] ?>" name='post_id'>
                      <button class='btn btn-primary' type="button" data-bs-toggle='modal' data-bs-target='#edit-category-modal<?= $i ?>'><i class='bi bi-pencil'></i></button>
                      <button class='btn btn-danger' type="submit" name="delete"><i class='bi bi-trash'></i></button>
                    </form>
                  </td>
                </tr>
                <div class='modal  fade' id='edit-category-modal<?= $i ?>' tabindex='-1' style='display: none;' aria-hidden='true'>
                  <div class='modal-dialog  modal-lg modal-dialog-centered'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title'>Update Post(
                          <?= $i ?>)
                        </h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <form method="post" id='edit-form' enctype="multipart/form-data">
                        <div class='modal-body'>
                          <input class='form-control' value="<?= $row["post_id"] ?>" type='hidden' name='post_id'>
                          <div class='form-group row '>
                            <div class="col-4">
                              <label for='full_name'>Post Title</label>
                            </div>
                            <div class="col-8">
                              <input class='form-control' value="<?= $row["title"] ?>" type='text' name='title'>
                            </div>
                          </div>
                          <div class='form-group row '>
                            <div class="col-4">
                              <label for='full_name'>Post Content</label>
                            </div>
                            <div class="col-8">
                              <textarea class='form-control' name="content" id="" cols="30" rows="5"><?= $row["content"] ?></textarea>
                              <!-- <input class='form-control' value="<?= $row["content"] ?>" type='text' name='content'> -->
                            </div>
                          </div>
                          <div class='form-group row '>
                            <div class="col-4">
                              <label for="floatingCategory">Select Category</label>
                            </div>
                            <div class="col-8">
                              <select name="category" id="floatingCategory" class="form-select">
                                <?php
                                $query = "SELECT * FROM Categories";
                                $runquery = mysqli_query($conn, $query);

                                while ($row1 = mysqli_fetch_assoc($runquery)) {
                                  $category = $row1["name"];
                                  $category_id = $row1["category_id"];
                                  $selected = $row["category_id"] == $category_id ? 'selected' : '';
                                  echo "
                                     <option value='$category_id'  $selected >$category</option>
                                  ";
                                }
                                ?>
                              </select>
                            </div>
                          </div>

                          <div class='form-group row m-0 mt-2'>
                            <label class="col-4 my-2" for='image'>Profile image</label>
                            <div class="col-8">
                              <input type="file" accept="image/*" onchange="loadFile<?= $i ?>(event)" id="image" class="form-control" name="image">
                            </div>
                          </div>
                          <div class="text-center mt-3 ">
                            <img class="rounded-5" id="output<?= $i ?>" height="120px" width="120px" src="<?= "." . $row["image"] ?>" alt="">
                            <script>
                              var loadFile<?= $i ?> = function(event) {
                                var output = document.getElementById('output<?= $i ?>');
                                output.src = URL.createObjectURL(event.target.files[0]);
                                output.onload = function() {
                                  URL.revokeObjectURL(output.src) // free memory
                                }
                              };
                            </script>
                          </div>
                        </div>
                        <div class='modal-footer'>
                          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                          <button type='submit' name="submit" class='btn btn-primary'>Save changes</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php
                $i++;
              }
              ?>

            </tbody>
          </table>
          <!-- End Table with stripped rows -->
        </div>
      </div> <!-- Left side columns -->
    </div>
    <div class='modal fade' id='edit-category-modal' style='display: none;' aria-hidden='true'>
      <div class='modal-dialog modal-lg modal-dialog-centered'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title'>Add Post</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <form method="post" id='edit-form' enctype="multipart/form-data">
            <div class='modal-body'>
              <input class='form-control' type='hidden' name='post_id'>
              <div class='form-group row '>
                <div class="col-4">
                  <label for='full_name'>Post Title</label>
                </div>
                <div class="col-8">
                  <input class='form-control' type='text' name='title'>
                </div>
              </div>
              <div class='form-group row '>
                <div class="col-4">
                  <label for='full_name'>Post Content</label>
                </div>
                <div class="col-8">
                  <textarea class='form-control' name="content" id="" cols="30" rows="5"></textarea>
                  <!-- <input class='form-control'  type='text' name='content'> -->
                </div>
              </div>
              <div class='form-group row '>
                <div class="col-4">
                  <label for="floatingCategory">Select Category</label>
                </div>
                <div class="col-8">
                  <select name="category" id="floatingCategory" class="form-select">
                    <?php
                    $query = "SELECT * FROM Categories";
                    $runquery = mysqli_query($conn, $query);

                    while ($row1 = mysqli_fetch_assoc($runquery)) {
                      $category = $row1["name"];
                      $category_id = $row1["category_id"];
                      $selected = $row["category"] == $category_id ? 'selected' : '';
                      echo "
                                     <option value='$category_id'  $selected >$category</option>
                                  ";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class='form-group row m-0 mt-2'>
                <label class="col-4 my-2" for='image'>Profile image</label>
                <div class="col-8">
                  <input type="file" accept="image/*" onchange="loadFile(event)" id="image" class="form-control" required name="image">
                </div>
              </div>
              <div class="text-center mt-3 ">
                <img class="rounded-5" id="output-save" height="120px" width="120px" alt="">
                <script>
                  var loadFile = function(event) {
                    var output = document.getElementById('output-save');
                    output.src = URL.createObjectURL(event.target.files[0]);
                    output.onload = function() {
                      URL.revokeObjectURL(output.src) // free memory
                    }
                  };
                </script>
              </div>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
              <button type='submit' name="insert" class='btn btn-primary'>Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main><!-- End #main -->