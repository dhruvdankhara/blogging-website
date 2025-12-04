<?php
include "connection.php";
session_start();

if (isset($_SESSION["user_id"])) {
  header("location:./dashboard.php");
}

if (isset($_POST["submit"])) {
  $email = $_POST["email"];
  $passwd = $_POST["passwd"];

  //  Query the database for the user
  $query = "SELECT * FROM Users WHERE email='$email'";
  $result = mysqli_query($conn, $query);

  //  Check if a user with that email exists
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION["user_id"] = $row["user_id"];

    //  Verify the password
    if (password_verify($passwd, $row["password"])) {
      if ($row['user_type'] == "admin") {
        header("location:admin/dashboard.php");
      } else {
        header("location:./dashboard.php");
      }
    } else {
      //  Invalid credentials
      $message[] = array(
        'icon' => 'error',
        'type' => 'Login',
        'message' => 'Invalid credentials!'
      );
    }
  } else {
    $message[] = array(
      'icon' => 'error',
      'type' => 'Login',
      'message' => 'User not exist'
    );
  }
}

use PHPMailer\PHPMailer\PHPMailer;

require 'lib/Exception.php';
require 'lib/PHPMailer.php';
require 'lib/SMTP.php';

$mail = new PHPMailer(true);

if (isset($_POST["sendMail"])) {
  //  Get email and generate a random token
  $email = $_POST['email'];
  $token = bin2hex(random_bytes(32)); // Generate a random token

  //  Check if the email exists in the database
  $q = "SELECT * FROM users WHERE email = '$email'";
  $rq = mysqli_query($conn, $q);
  $count = mysqli_num_rows($rq);

  if ($count) {
    //  If email exists, update the user's token
    $user_data = mysqli_fetch_assoc($rq);

    // Store token in database with timestamp for security
    $q = "UPDATE users SET token = '$token', token_created_at = NOW() WHERE user_id = {$user_data['user_id']}";
    $rq = mysqli_query($conn, $q);

    if ($rq) {
      //  Prepare and send the password reset email
      $resetUrl = "http://" . $_SERVER['HTTP_HOST'] . "/blogging-website/change-password.php?token=$token";

      // Set up PHPMailer
      $mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Username = "jropox7272@gmail.com";  
$mail->Password = "etpjpdrlktjuiewu";  // Gmail App Password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

// SSL FIX for XAMPP
$mail->SMTPOptions = [
  "ssl" => [
    "verify_peer" => false,
    "verify_peer_name" => false,
    "allow_self_signed" => true
  ]
];


      $mail->addAddress($email);
      $mail->isHTML(true);
      $mail->Subject = 'Password Reset';

      // $mail->Body = "hello";
      $mail->Body = "
      <div style='font-family: Arial, sans-serif; text-align: center;'>
        <h2 style='color: #333;'>Password Reset</h2>
        <p style='font-size: 18px; color: #555;'>
          We've received a request to reset your password. <br> Click the link below to proceed.
        </p>
        <p style='background-color: #007BFF; padding: 10px; display: inline-block; border-radius: 5px;'>
          <a href='$resetUrl' style='color: #fff; text-decoration: none;'>Reset Password</a>
        </p>
      </div>
    ";

      // Send email
      if (!$mail->send()) {
        $message[] = array(
          'icon' => 'error',
          'type' => 'Send Email',
          'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo
        );
      } else {
        $message[] = array(
          'icon' => 'success',
          'type' => 'Send Email',
          'message' => 'Check your email for a password reset link.'
        );
      }
    } else {
      //  Handle errors when updating token
      $message[] = array(
        'icon' => 'error',
        'type' => 'Token',
        'message' => 'Something went wrong'
      );
    }
  } else {
    //  Handle case where email is not found in the database
    $message[] = array(
      'icon' => 'error',
      'type' => 'User',
      'message' => 'User not found.'
    );
  }
}


include "./alert_message.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogHive - Share Your Story with the World</title>
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
      <div class="col-sm-10 col-md-7 col-lg-5">
        <div class="auth-card p-4 p-md-5 w-100 m-auto">
          <form method="POST" id="loginForm" onsubmit="return validateLoginForm()">
            <div class="text-center">
              <h4 class="mb-3 fw-semibold text-brand">Welcome back</h4>
            </div>

            <div class="form-floating my-2">
              <input type="text" name="email" class="form-control" id="email" placeholder="name@example.com">
              <label for="email">Enter Email</label>
              <div class="invalid-feedback" id="loginEmailError"></div>
            </div>
            <div class="form-floating my-2">
              <input type="password" name="passwd" class="form-control" id="password" placeholder="Password">
              <label for="password">Enter Password</label>
              <div class="invalid-feedback" id="loginPasswordError"></div>
            </div>
            <small class="text-end d-block"><a data-bs-toggle="modal" data-bs-target="#exampleModal" href="">Forgot
                password</a></small>
            <button class="btn btn-custom w-100 py-2 my-3" name="submit" type="submit">Log in</button>
            <div class="text-center">
              <small>Don't have an account? &nbsp;<a href="./signup.php">Sign Up</a></small>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" class="text-center" action="index.php" id="forgotPasswordForm"
            onsubmit="return validateForgotPasswordForm()">
            <div class="form-floating my-2">
              <input type="text" name="email" class="form-control" id="forgotPasswordEmail"
                placeholder="name@example.com">
              <label for="forgotPasswordEmail">Enter Email</label>
              <div class="invalid-feedback" id="forgotPasswordError"></div>
            </div>
            <button class="btn btn-custom w-100 py-2 my-3" name="sendMail" type="submit">Send link</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function validateLoginForm() {
      let isValid = true;

      // Clear previous errors
      clearLoginErrors();

      // Validate email
      const email = document.getElementById('email').value.trim();

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (email === '') {
        showLoginError('email', 'loginEmailError', 'Email is required');
        isValid = false;
      } else if (!emailRegex.test(email)) {
        showLoginError('email', 'loginEmailError', 'Please enter a valid email address');
        isValid = false;
      }

      // Validate password
      const password = document.getElementById('password').value;

      if (password === '') {
        showLoginError('password', 'loginPasswordError', 'Password is required');
        isValid = false;
      }

      return isValid;
    }

    function validateForgotPasswordForm() {
      const email = document.getElementById('forgotPasswordEmail').value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      // Clear previous error
      document.getElementById('forgotPasswordEmail').classList.remove('is-invalid');
      document.getElementById('forgotPasswordError').style.display = 'none';

      if (email === '') {
        showLoginError('forgotPasswordEmail', 'forgotPasswordError', 'Email is required');
        return false;
      } else if (!emailRegex.test(email)) {
        showLoginError('forgotPasswordEmail', 'forgotPasswordError', 'Please enter a valid email address');
        return false;
      }

      return true;
    }

    function showLoginError(inputId, errorId, message) {
      const input = document.getElementById(inputId);
      const error = document.getElementById(errorId);

      input.classList.add('is-invalid');
      error.textContent = message;
      error.style.display = 'block';
    }

    function clearLoginErrors() {
      const inputs = ['email', 'password'];
      const errors = ['loginEmailError', 'loginPasswordError'];

      inputs.forEach(inputId => {
        document.getElementById(inputId).classList.remove('is-invalid');
      });

      errors.forEach(errorId => {
        const error = document.getElementById(errorId);
        error.textContent = '';
        error.style.display = 'none';
      });
    }

    // Real-time validation for login form
    document.getElementById('email').addEventListener('input', function () {
      if (this.classList.contains('is-invalid')) {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email !== '' && emailRegex.test(email)) {
          this.classList.remove('is-invalid');
          document.getElementById('loginEmailError').style.display = 'none';
        }
      }
    });

    document.getElementById('password').addEventListener('input', function () {
      if (this.classList.contains('is-invalid')) {
        const password = this.value;

        if (password !== '') {
          this.classList.remove('is-invalid');
          document.getElementById('loginPasswordError').style.display = 'none';
        }
      }
    });

    // Real-time validation for forgot password
    document.getElementById('forgotPasswordEmail').addEventListener('input', function () {
      if (this.classList.contains('is-invalid')) {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email !== '' && emailRegex.test(email)) {
          this.classList.remove('is-invalid');
          document.getElementById('forgotPasswordError').style.display = 'none';
        }
      }
    });
  </script>

</body>

</html>