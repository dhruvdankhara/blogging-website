<?php
include "connection.php";

if (isset($_POST["submit"])) {
  // echo "form submitted!";
  $name = $_POST["name"];
  $email = $_POST["email"];
  $passwd = $_POST["passwd"];
  $image = $_FILES["profile"]["name"];
  $tempname = $_FILES['profile']['tmp_name'];

  if (strlen($passwd) < 6) {
    $message[] = array(
      'icon' => 'error',
      'type' => 'Password Length',
      'message' => 'Password must be at least 6 characters long.'
    );
  } else {
    $hash_passwd = password_hash($passwd, PASSWORD_DEFAULT);

    $path = "./upload/profile/" . time() . $image;
    $query = "select * from Users where email = '$email'";
    $runquery = mysqli_query($conn, $query);
    if (mysqli_num_rows($runquery) == 0) {

      $allowed_image_extension = array(
        "png",
        "jpg",
        "jpeg"
      );
      $file_extension = pathinfo($_FILES["profile"]["name"], PATHINFO_EXTENSION);
      // copy($tempname, $path);

      if (!in_array($file_extension, $allowed_image_extension)) {
        $message[] = array(
          'icon' => 'error',
          'type' => 'Error',
          'message' => 'Upload valid images. Only PNG and JPEG are allowed.'
        );
      } else if (move_uploaded_file($tempname, $path)) {
        $query = "INSERT INTO Users(name,email,password,image) VALUES('$name','$email','$hash_passwd','$path')";
        $runquery = mysqli_query($conn, $query);
        if ($runquery) {
          $message[] = array(
            'icon' => 'success',
            'type' => 'Register',
            'message' => 'Registred successfully!',
            'redirection' => 'index.php'    // this code redirect to index.php page after signup
          );
          include "./alert_message.php";
          // header("location:./index.php");
        }
      } else {
        $message[] = array(
          'icon' => 'error',
          'type' => 'Upload Image',
          'message' => 'Failed to upload image!'
        );
      }
    } else {
      $message[] = array(
        'icon' => 'error',
        'type' => 'Already Exist',
        'message' => 'User already exist'
      );
    }
  }

  // echo $userid.$name.$email.$passwd;
  include "./alert_message.php";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogHive</title>
  <link rel="icon" href="./assets/website_logo-removebg-preview.png" type="image/png">
  <link href="./lib/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./vendor/css/style.css">
  <link rel="stylesheet" href="./vendor/css/theme.css">

</head>

<body>
  <?php
  include "./navbar_root.php";
  ?>

  <div class="container page-section">
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-7 col-lg-4">
        <div class="auth-card p-4 p-md-5 w-100 m-auto">
          <form method="POST" enctype="multipart/form-data" id="signupForm" onsubmit="return validateSignupForm()">
            <div class="text-center">
              <h4 class="mb-3 fw-semibold text-brand">Create your account</h4>
            </div>
            <!-- <h1 class="h3 mb-3 fw-bold">Please fill up this form</h1> -->

            <div class="form-floating my-2">
              <input type="text" name="name" class="form-control" id="floatingName" placeholder="name surname">
              <label for="floatingName">Enter Name</label>
              <div class="invalid-feedback" id="nameError"></div>
            </div>
            <div class="form-floating my-2">
              <input type="text" name="email" class="form-control" id="floatingEmail" placeholder="name@example.com">
              <label for="floatingEmail">Enter Email</label>
              <div class="invalid-feedback" id="emailError"></div>
            </div>
            <div class="form-floating my-2">
              <input type="password" name="passwd" class="form-control" id="floatingPassword" placeholder="Password">
              <label for="floatingPassword">Enter Password</label>
              <div class="invalid-feedback" id="passwordError"></div>
            </div>
            <div class="form-floating my-2">
              <input type="file" name="profile" class="form-control" id="floatingFile" accept="image/*">
              <label for="floatingFile">Upload Profile Picture</label>
              <div class="invalid-feedback" id="fileError"></div>
            </div>
            <button class="btn btn-custom w-100 py-2 my-3" name="submit" type="submit">Sign up</button>
            <div class="text-center">
              <small>Already have an account? &nbsp;<a href="./index.php">Log In</a></small>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function validateSignupForm() {
      let isValid = true;
      
      // Clear previous errors
      clearErrors();
      
      // Validate name
      const name = document.getElementById('floatingName').value.trim();
      if (name === '') {
        showError('floatingName', 'nameError', 'Name is required');
        isValid = false;
      } else if (name.length < 2) {
        showError('floatingName', 'nameError', 'Name must be at least 2 characters long');
        isValid = false;
      }
      
      // Validate email
      const email = document.getElementById('floatingEmail').value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (email === '') {
        showError('floatingEmail', 'emailError', 'Email is required');
        isValid = false;
      } else if (!emailRegex.test(email)) {
        showError('floatingEmail', 'emailError', 'Please enter a valid email address');
        isValid = false;
      }
      
      // Validate password
      const password = document.getElementById('floatingPassword').value;
      if (password === '') {
        showError('floatingPassword', 'passwordError', 'Password is required');
        isValid = false;
      } else if (password.length < 6) {
        showError('floatingPassword', 'passwordError', 'Password must be at least 6 characters long');
        isValid = false;
      } else if (!/(?=.*[a-z])/.test(password)) {
        showError('floatingPassword', 'passwordError', 'Password must contain at least one lowercase letter');
        isValid = false;
      } else if (!/(?=.*[A-Z])/.test(password)) {
        showError('floatingPassword', 'passwordError', 'Password must contain at least one uppercase letter');
        isValid = false;
      } else if (!/(?=.*\d)/.test(password)) {
        showError('floatingPassword', 'passwordError', 'Password must contain at least one number');
        isValid = false;
      }
      
      // Validate file
      const fileInput = document.getElementById('floatingFile');
      const file = fileInput.files[0];
      if (!file) {
        showError('floatingFile', 'fileError', 'Profile picture is required');
        isValid = false;
      } else {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
          showError('floatingFile', 'fileError', 'Only JPEG, JPG, and PNG files are allowed');
          isValid = false;
        } else if (file.size > 5 * 1024 * 1024) { // 5MB limit
          showError('floatingFile', 'fileError', 'File size should not exceed 5MB');
          isValid = false;
        }
      }
      
      return isValid;
    }
    
    function showError(inputId, errorId, message) {
      const input = document.getElementById(inputId);
      const error = document.getElementById(errorId);
      
      input.classList.add('is-invalid');
      error.textContent = message;
      error.style.display = 'block';
    }
    
    function clearErrors() {
      const inputs = ['floatingName', 'floatingEmail', 'floatingPassword', 'floatingFile'];
      const errors = ['nameError', 'emailError', 'passwordError', 'fileError'];
      
      inputs.forEach(inputId => {
        document.getElementById(inputId).classList.remove('is-invalid');
      });
      
      errors.forEach(errorId => {
        const error = document.getElementById(errorId);
        error.textContent = '';
        error.style.display = 'none';
      });
    }
    
    // Real-time validation
    document.getElementById('floatingName').addEventListener('input', function() {
      if (this.classList.contains('is-invalid')) {
        const name = this.value.trim();
        if (name !== '' && name.length >= 2) {
          this.classList.remove('is-invalid');
          document.getElementById('nameError').style.display = 'none';
        }
      }
    });
    
    document.getElementById('floatingEmail').addEventListener('input', function() {
      if (this.classList.contains('is-invalid')) {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email !== '' && emailRegex.test(email)) {
          this.classList.remove('is-invalid');
          document.getElementById('emailError').style.display = 'none';
        }
      }
    });
    
    document.getElementById('floatingPassword').addEventListener('input', function() {
      if (this.classList.contains('is-invalid')) {
        const password = this.value;
        const hasLowercase = /(?=.*[a-z])/.test(password);
        const hasUppercase = /(?=.*[A-Z])/.test(password);
        const hasNumber = /(?=.*\d)/.test(password);
        
        if (password !== '' && password.length >= 6 && hasLowercase && hasUppercase && hasNumber) {
          this.classList.remove('is-invalid');
          document.getElementById('passwordError').style.display = 'none';
        }
      }
    });
    
    document.getElementById('floatingFile').addEventListener('change', function() {
      if (this.classList.contains('is-invalid')) {
        const file = this.files[0];
        if (file) {
          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
          if (allowedTypes.includes(file.type) && file.size <= 5 * 1024 * 1024) {
            this.classList.remove('is-invalid');
            document.getElementById('fileError').style.display = 'none';
          }
        }
      }
    });
  </script>
</body>

</html>