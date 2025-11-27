<?php
require_once("./connection.php");
session_start();

if (isset($_POST["submit"])) {
    // Step 1: Get form data
    $new_password = $_POST["new_password"];
    $renew_password = $_POST["renew_password"];
    $token = $_GET["token"];

    // Step 2: Validate password length
    if (strlen($new_password) < 6) {
        $message[] = array(
            'icon' => 'error',
            'type' => 'Password Length',
            'message' => 'Password must be at least 6 characters long.'
        );
        $isSuccess = false;
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        $message[] = array(
            'icon' => 'error',
            'type' => 'Password Validation',
            'message' => 'Password must contain at least one lowercase letter.'
        );
        $isSuccess = false;
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        $message[] = array(
            'icon' => 'error',
            'type' => 'Password Validation',
            'message' => 'Password must contain at least one uppercase letter.'
        );
        $isSuccess = false;
    } elseif (!preg_match('/\d/', $new_password)) {
        $message[] = array(
            'icon' => 'error',
            'type' => 'Password Validation',
            'message' => 'Password must contain at least one number.'
        );
        $isSuccess = false;
    } else {
        // Step 3: Check if token is valid in database
        $q = "SELECT * FROM users WHERE token='$token'";
        $rq = mysqli_query($conn, $q);
        $count = mysqli_num_rows($rq);

        if ($count) {
            // Step 4: Check if passwords match
            if ($new_password != $renew_password) {
                $message[] = array(
                    'icon' => 'error',
                    'type' => 'Reset Password',
                    'message' => 'New password and confirm password do not match.'
                );
                $isSuccess = false;
            } else {
                // Step 5: Hash new password and update in database
                $hash_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password and clear token
                $query = "UPDATE users SET password='$hash_new_password', token=NULL WHERE token='$token'";
                $runquery = mysqli_query($conn, $query);

                if ($runquery) {
                    // Step 6: Password reset successful
                    $message[] = array(
                        'icon' => 'success',
                        'type' => 'Reset Password',
                        'message' => 'Password reset successfully!',
                        'redirection' => 'index.php'
                    );
                    $isSuccess = true;
                } else {
                    // Step 7: Unable to reset password
                    $message[] = array(
                        'icon' => 'error',
                        'type' => 'Reset Password',
                        'message' => 'Unable to Reset Password!'
                    );
                    $isSuccess = false;
                }
            }
        } else {
            // Step 8: Token is not valid
            $message[] = array(
                'icon' => 'error',
                'type' => 'Reset Password',
                'message' => 'Invalid Details Provide'
            );
            $isSuccess = false;
        }
    }
}

include "./alert_message.php";
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
            <div class="col-12 col-md-8 col-lg-5">
                <div class="auth-card p-4 p-md-5">
                    <form method="POST">
                        <div class="text-center mb-3">
                            <img class="mb-2" src="./assets/website_logo-removebg-preview.png" alt="BlogHive" width="96"
                                height="96">
                            <h4 class="mt-2 mb-0 fw-semibold text-brand">Reset password</h4>
                            <p class="text-muted small mb-0">Enter your new password below</p>
                        </div>

                        <div class="form-floating my-3">
                            <input type="password" name="new_password" class="form-control" id="newPassword"
                                placeholder="New password" required>
                            <label for="newPassword">New password</label>
                            <div class="invalid-feedback" id="newPasswordError"></div>
                        </div>
                        <div class="form-floating my-3">
                            <input type="password" name="renew_password" class="form-control" id="confirmPassword"
                                placeholder="Confirm password" required>
                            <label for="confirmPassword">Confirm new password</label>
                            <div class="invalid-feedback" id="confirmPasswordError"></div>
                        </div>
                        <button class="btn btn-custom w-100 py-2 my-2" name="submit" type="submit">Update
                            password</button>
                        <div class="text-center mt-2">
                            <small class="text-center">Remembered your password? &nbsp;<a href="./index.php">Log
                                    in</a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            let isValid = true;

            // Clear previous errors
            clearErrors();

            // Validate new password
            const newPassword = document.getElementById('newPassword').value;
            if (newPassword === '') {
                showError('newPassword', 'newPasswordError', 'Password is required');
                isValid = false;
            } else if (newPassword.length < 6) {
                showError('newPassword', 'newPasswordError', 'Password must be at least 6 characters long');
                isValid = false;
            } else if (!/(?=.*[a-z])/.test(newPassword)) {
                showError('newPassword', 'newPasswordError', 'Password must contain at least one lowercase letter');
                isValid = false;
            } else if (!/(?=.*[A-Z])/.test(newPassword)) {
                showError('newPassword', 'newPasswordError', 'Password must contain at least one uppercase letter');
                isValid = false;
            } else if (!/(?=.*\d)/.test(newPassword)) {
                showError('newPassword', 'newPasswordError', 'Password must contain at least one number');
                isValid = false;
            }

            // Validate confirm password
            const confirmPassword = document.getElementById('confirmPassword').value;
            if (confirmPassword === '') {
                showError('confirmPassword', 'confirmPasswordError', 'Please confirm your password');
                isValid = false;
            } else if (newPassword !== confirmPassword) {
                showError('confirmPassword', 'confirmPasswordError', 'Passwords do not match');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        function showError(inputId, errorId, message) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);

            input.classList.add('is-invalid');
            error.textContent = message;
            error.style.display = 'block';
        }

        function clearErrors() {
            const inputs = ['newPassword', 'confirmPassword'];
            const errors = ['newPasswordError', 'confirmPasswordError'];

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
        document.getElementById('newPassword').addEventListener('input', function () {
            if (this.classList.contains('is-invalid')) {
                const password = this.value;
                const hasLowercase = /(?=.*[a-z])/.test(password);
                const hasUppercase = /(?=.*[A-Z])/.test(password);
                const hasNumber = /(?=.*\d)/.test(password);

                if (password !== '' && password.length >= 6 && hasLowercase && hasUppercase && hasNumber) {
                    this.classList.remove('is-invalid');
                    document.getElementById('newPasswordError').style.display = 'none';
                }
            }
        });

        document.getElementById('confirmPassword').addEventListener('input', function () {
            if (this.classList.contains('is-invalid')) {
                const newPassword = document.getElementById('newPassword').value;
                const confirmPassword = this.value;

                if (confirmPassword !== '' && newPassword === confirmPassword) {
                    this.classList.remove('is-invalid');
                    document.getElementById('confirmPasswordError').style.display = 'none';
                }
            }
        });
    </script>
</body>

</html>