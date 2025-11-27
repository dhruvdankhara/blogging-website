<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogHive</title>
  <link href="./lib/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./vendor/css/style.css">
  <link rel="stylesheet" href="./vendor/css/theme.css">
  <link rel="icon" href="./assets/website_logo-removebg-preview.png" type="image/png">

</head>

<body>
  <?php
  include "./navbar_dash.php";

  $user_id = $_SESSION["user_id"];

  if (isset($_POST["submit"])) {
    // echo "form submitted";
  
    $title = $_POST["title"];
    $content = $_POST["content"];
    $category_id = $_POST["category_id"];
    $image = $_FILES["image"]["name"];

    // echo $image;
    $tmpname = $_FILES["image"]["tmp_name"];
    $path = "./upload/post/" . time() . $image;

    $allowed_image_extension = array(
      "png",
      "jpg",
      "jpeg"
    );
    $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

    if (!in_array($file_extension, $allowed_image_extension)) {
      $message[] = array(
        'icon' => 'error',
        'type' => 'Error',
        'message' => 'Upload valid images. Only PNG and JPEG are allowed.'
      );
    } else if (move_uploaded_file($tmpname, $path)) {
      $query = "INSERT INTO blog_posts (user_id,title,content,image,category_id,created_at) VALUES($user_id,'$title','$content','$path',$category_id,NOW())";

      // echo $query;
  

      $runquery = mysqli_query($conn, $query);

      if ($runquery) {
        $message[] = array(
          'icon' => 'success',
          'type' => 'Blog Post Add',
          'message' => 'Post added successfully!'
        );
      }
    } else {
      $message[] = array(
        'icon' => 'error',
        'type' => 'Blog Post',
        'message' => 'Failed to upload image!'
      );
    }
  }

  include "./alert_message.php";
  ?>

  <div class="container">
    <div class="row">
      <div class="col-sm-12 m-auto">
        <div class="form-signin w-100 m-auto ">
          <form method="post" class="text-center " enctype="multipart/form-data" id="addBlogForm"
            onsubmit="return validateBlogForm()">
            <!-- <img class="mt-4 mb-2" src="./assets/website_logo-removebg-preview.png" alt="" width="100"> -->
            <h1 class="h3 mt-4 mb-2 fw-bold text-custom" style="color: black;">Add Blog Post Data</h1>
            <div class="form row">
              <div class="col-12">
                <div class="form-floating my-2">
                  <input type="text" name="title" class="form-control" id="floatingInput" placeholder="Title">
                  <label for="floatingInput">Enter Title</label>
                  <div class="invalid-feedback" id="titleError"></div>
                </div>
                <div class="form-group my-2">
                  <textarea type="content" placeholder="Write content here...." name="content" class="form-control"
                    id="contentTextarea" rows="4"></textarea>
                  <div class="invalid-feedback" id="contentError"></div>
                </div>
                <div class="form-floating my-2">
                  <select name="category_id" id="floatingCategory" class="form-select">
                    <?php
                    $query = "SELECT * FROM categories";
                    $runquery = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($runquery)) {
                      $category = $row["name"];
                      $category_id = $row["category_id"];

                      echo "
                    <option value='$category_id'>$category</option>
                    ";
                    }
                    ?>
                  </select>
                  <label for="floatingCategory">Select Category</label>
                </div>
              </div>
              <div class="col-sm-6">

                <span class="form-title">Upload blog image</span>
                <p class="form-paragraph">
                  File should be an image
                </p>
                <label for="file-input" class="drop-container">
                  <span class="drop-title">Drop files here</span>
                  or
                  <input type="file" name="image" accept="image/*" id="file-input"
                    onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])">
                  <div class="invalid-feedback" id="imageError"></div>
                </label>
              </div>
              <div class="col-sm-6 ">
                <img class="rounded-5" id="output" src="" alt="blog_image" style="width:100%;">
              </div>
              <div class="form-group">
                <button class="btn btn-custom btn-lg py-2 my-3" name="submit" value="insertSubmit" type="submit">Post
                  Blog</button>
                <a href="add_to_wishlist.php">
                  <!-- <button class="btn btn-custom py-2 my-3" name="submit" type="submit">Add To Wishlist</button> -->
                </a>
              </div>
            </div>



          </form>
          <script>
            function validateBlogForm() {
              let isValid = true;
              clearBlogErrors();
              const title = document.getElementById('floatingInput').value.trim();
              if (title === '') {
                showBlogError('floatingInput', 'titleError', 'Title is required');
                isValid = false;
              } else if (title.length < 5) {
                showBlogError('floatingInput', 'titleError', 'Title must be at least 5 characters long');
                isValid = false;
              } else if (title.length > 200) {
                showBlogError('floatingInput', 'titleError', 'Title must not exceed 200 characters');
                isValid = false;
              }
              const content = document.getElementById('contentTextarea').value.trim();
              if (content === '') {
                showBlogError('contentTextarea', 'contentError', 'Content is required');
                isValid = false;
              } else if (content.length < 20) {
                showBlogError('contentTextarea', 'contentError', 'Content must be at least 20 characters long');
                isValid = false;
              } else if (content.length > 5000) {
                showBlogError('contentTextarea', 'contentError', 'Content must not exceed 5000 characters');
                isValid = false;
              }
              const fileInput = document.getElementById('file-input');
              const file = fileInput.files[0];
              if (!file) {
                showBlogError('file-input', 'imageError', 'Blog image is required');
                isValid = false;
              } else {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                  showBlogError('file-input', 'imageError', 'Only JPEG, JPG, and PNG files are allowed');
                  isValid = false;
                } else if (file.size > 10 * 1024 * 1024) {
                  showBlogError('file-input', 'imageError', 'File size should not exceed 10MB');
                  isValid = false;
                }
              }
              return isValid;
            }
            function showBlogError(inputId, errorId, message) {
              const input = document.getElementById(inputId);
              const error = document.getElementById(errorId);
              input.classList.add('is-invalid');
              error.textContent = message;
              error.style.display = 'block';
            }
            function clearBlogErrors() {
              const inputs = ['floatingInput', 'contentTextarea', 'file-input'];
              const errors = ['titleError', 'contentError', 'imageError'];
              inputs.forEach(inputId => {
                document.getElementById(inputId).classList.remove('is-invalid');
              });
              errors.forEach(errorId => {
                const error = document.getElementById(errorId);
                error.textContent = '';
                error.style.display = 'none';
              });
            }
            document.getElementById('floatingInput').addEventListener('input', function () {
              if (this.classList.contains('is-invalid')) {
                const title = this.value.trim();
                if (title !== '' && title.length >= 5 && title.length <= 200) {
                  this.classList.remove('is-invalid');
                  document.getElementById('titleError').style.display = 'none';
                }
              }
            });
            document.getElementById('contentTextarea').addEventListener('input', function () {
              if (this.classList.contains('is-invalid')) {
                const content = this.value.trim();
                if (content !== '' && content.length >= 20 && content.length <= 5000) {
                  this.classList.remove('is-invalid');
                  document.getElementById('contentError').style.display = 'none';
                }
              }
            });
            document.getElementById('file-input').addEventListener('change', function () {
              if (this.classList.contains('is-invalid')) {
                const file = this.files[0];
                if (file) {
                  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                  if (allowedTypes.includes(file.type) && file.size <= 10 * 1024 * 1024) {
                    this.classList.remove('is-invalid');
                    document.getElementById('imageError').style.display = 'none';
                  }
                }
              }
            });
          </script>
        </div>
      </div>
    </div>
  </div>
</body>


</html>