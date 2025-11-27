<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogHive</title>
    <link href="./lib/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./vendor/css/style.css">
    <link rel="stylesheet" href="./vendor/css/theme.css">
    <link rel="stylesheet" href="./vendor/css/profile.css">
    <link rel="icon" href="./assets/website_logo-removebg-preview.png" type="image/png">

</head>

<body>
    <?php
    require_once "./connection.php";

    // Handle Profile Details Update (Name & Email only)
    if (isset($_POST["update_profile"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $user_id = $_POST["user_id"];

        $query = "UPDATE users SET name='$username', email='$email' WHERE user_id=$user_id";
        $runquery = mysqli_query($conn, $query);

        if ($runquery) {
            // Refresh user data
            $query = "SELECT * FROM users WHERE user_id='$user_id'";
            $runquery = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($runquery);

            $message[] = array(
                'icon' => 'success',
                'type' => 'Update Profile',
                'message' => 'Profile details updated successfully!'
            );
        } else {
            $message[] = array(
                'icon' => 'error',
                'type' => 'Update Profile',
                'message' => 'Unable to update profile!'
            );
        }
    }

    // Handle Password Change
    if (isset($_POST["change_password"])) {
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];
        $user_id = $_POST["user_id"];

        // Get current user data
        $q = "SELECT * FROM users WHERE user_id=$user_id";
        $rq = mysqli_query($conn, $q);
        $user_data = mysqli_fetch_assoc($rq);

        if (strlen($new_password) < 6) {
            $message[] = array(
                'icon' => 'error',
                'type' => 'Password Change',
                'message' => 'Password must be at least 6 characters long.'
            );
        } elseif (!preg_match('/[a-z]/', $new_password)) {
            $message[] = array(
                'icon' => 'error',
                'type' => 'Password Change',
                'message' => 'Password must contain at least one lowercase letter.'
            );
        } elseif (!preg_match('/[A-Z]/', $new_password)) {
            $message[] = array(
                'icon' => 'error',
                'type' => 'Password Change',
                'message' => 'Password must contain at least one uppercase letter.'
            );
        } elseif (!preg_match('/\d/', $new_password)) {
            $message[] = array(
                'icon' => 'error',
                'type' => 'Password Change',
                'message' => 'Password must contain at least one number.'
            );
        } elseif (!password_verify($current_password, $user_data["password"])) {
            $message[] = array(
                'icon' => 'error',
                'type' => 'Password Change',
                'message' => 'Current password is incorrect!'
            );
        } elseif ($new_password !== $confirm_password) {
            $message[] = array(
                'icon' => 'error',
                'type' => 'Password Change',
                'message' => 'New passwords do not match!'
            );
        } else {
            $hash_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET password='$hash_new_password' WHERE user_id=$user_id";
            $runquery = mysqli_query($conn, $query);

            if ($runquery) {
                $message[] = array(
                    'icon' => 'success',
                    'type' => 'Password Change',
                    'message' => 'Password changed successfully! Please login again.',
                    'redirection' => 'logout.php'
                );
            } else {
                $message[] = array(
                    'icon' => 'error',
                    'type' => 'Password Change',
                    'message' => 'Unable to change password!'
                );
            }
        }
    }

    // Handle Profile Photo Update
    if (isset($_POST["update_photo"])) {
        $user_id = $_POST["user_id"];

        // Get current user data
        $q = "SELECT * FROM users WHERE user_id=$user_id";
        $rq = mysqli_query($conn, $q);
        $user_data = mysqli_fetch_assoc($rq);

        if ($_FILES["profile"]["name"]) {
            $img_name = $_FILES['profile']['name'];
            $path = "./upload/profile/" . time() . $img_name;

            $allowed_image_extension = array("png", "jpg", "jpeg");
            $file_extension = pathinfo($_FILES["profile"]["name"], PATHINFO_EXTENSION);

            if (!in_array($file_extension, $allowed_image_extension)) {
                $message[] = array(
                    'icon' => 'error',
                    'type' => 'Photo Update',
                    'message' => 'Upload valid images. Only PNG and JPEG are allowed.'
                );
            } else if (move_uploaded_file($_FILES['profile']['tmp_name'], $path)) {
                // Delete old image if exists
                if ($user_data["image"]) {
                    $removeFileName = $user_data["image"];
                    @unlink($removeFileName);
                }

                $query = "UPDATE users SET image='$path' WHERE user_id=$user_id";
                $runquery = mysqli_query($conn, $query);

                if ($runquery) {
                    // Refresh user data
                    $query = "SELECT * FROM users WHERE user_id='$user_id'";
                    $runquery = mysqli_query($conn, $query);
                    $row = mysqli_fetch_assoc($runquery);

                    $message[] = array(
                        'icon' => 'success',
                        'type' => 'Photo Update',
                        'message' => 'Profile photo updated successfully!'
                    );
                } else {
                    $message[] = array(
                        'icon' => 'error',
                        'type' => 'Photo Update',
                        'message' => 'Unable to update photo!'
                    );
                }
            }
        } else {
            $message[] = array(
                'icon' => 'error',
                'type' => 'Photo Update',
                'message' => 'Please select an image!'
            );
        }
    }
    include "./alert_message.php";
    ?>

    <?php
    include "./navbar_dash.php";
    ?>

    <main class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
            <small class="text-muted">Manage your account settings</small>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img src="<?= $row["image"] ?>" onerror="this.src='assets/default-profile.png'" 
                                 class="rounded-circle" width="120" height="120" style="object-fit: cover;" alt="Profile">
                        </div>
                        <h5 class="card-title mb-1"><?= $row["name"] ?></h5>
                        <p class="text-muted small mb-0">User</p>
                        <hr>
                        <div class="text-start">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Name:</span>
                                <span class="small"><?= $row["name"] ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Email:</span>
                                <span class="small"><?= $row["email"] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- 1. Update Profile Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i>Profile Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input class="form-control" value="<?= $row["name"] ?>" type="text" required name="username">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input class="form-control" value="<?= $row["email"] ?>" type="email" required name="email">
                                </div>
                            </div>

                            <div class="mt-3 text-end">
                                <button type="submit" name="update_profile" class="btn" style="background-color: #0d6efd; color: white;">
                                    <i class="bi bi-check-lg me-1"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 2. Change Password -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0"><i class="bi bi-lock me-2"></i>Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" id="changePasswordForm">
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Current Password</label>
                                    <input class="form-control" type="password" name="current_password" id="currentPassword" required>
                                    <div class="invalid-feedback" id="currentPasswordError"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <input class="form-control" type="password" name="new_password" id="newPassword" required>
                                    <small class="text-muted">Min 6 chars, with uppercase, lowercase & number</small>
                                    <div class="invalid-feedback" id="newPasswordError"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password</label>
                                    <input class="form-control" type="password" name="confirm_password" id="confirmPassword" required>
                                    <div class="invalid-feedback" id="confirmPasswordError"></div>
                                </div>
                            </div>

                            <div class="mt-3 text-end">
                                <button type="submit" name="change_password" class="btn btn-warning">
                                    <i class="bi bi-shield-lock me-1"></i>Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 3. Update Profile Photo -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0"><i class="bi bi-image me-2"></i>Profile Photo</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Choose New Photo</label>
                                    <input type="file" class="form-control" accept="image/*" name="profile" 
                                           onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])" required>
                                    <small class="text-muted">Allowed: PNG, JPG, JPEG</small>
                                </div>
                                <div class="col-12">
                                    <div class="text-center">
                                        <img class="rounded border" id="output" height="120" width="120" 
                                             src="<?= $row["image"] ?>" onerror="this.src='assets/default-profile.png'" style="object-fit: cover;">
                                        <p class="small text-muted mt-2">Image preview</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-end">
                                <button type="submit" name="update_photo" class="btn btn-success">
                                    <i class="bi bi-upload me-1"></i>Update Photo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Password change form validation
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            clearPasswordErrors();
            
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            // Validate current password
            if (currentPassword === '') {
                showPasswordError('currentPassword', 'currentPasswordError', 'Current password is required');
                isValid = false;
            }
            
            // Validate new password
            if (newPassword === '') {
                showPasswordError('newPassword', 'newPasswordError', 'New password is required');
                isValid = false;
            } else if (newPassword.length < 6) {
                showPasswordError('newPassword', 'newPasswordError', 'Password must be at least 6 characters long');
                isValid = false;
            } else if (!/(?=.*[a-z])/.test(newPassword)) {
                showPasswordError('newPassword', 'newPasswordError', 'Password must contain at least one lowercase letter');
                isValid = false;
            } else if (!/(?=.*[A-Z])/.test(newPassword)) {
                showPasswordError('newPassword', 'newPasswordError', 'Password must contain at least one uppercase letter');
                isValid = false;
            } else if (!/(?=.*\d)/.test(newPassword)) {
                showPasswordError('newPassword', 'newPasswordError', 'Password must contain at least one number');
                isValid = false;
            }
            
            // Validate confirm password
            if (confirmPassword === '') {
                showPasswordError('confirmPassword', 'confirmPasswordError', 'Please confirm your password');
                isValid = false;
            } else if (newPassword !== confirmPassword) {
                showPasswordError('confirmPassword', 'confirmPasswordError', 'Passwords do not match');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        function showPasswordError(inputId, errorId, message) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(errorId);
            
            input.classList.add('is-invalid');
            error.textContent = message;
            error.style.display = 'block';
        }
        
        function clearPasswordErrors() {
            const inputs = ['currentPassword', 'newPassword', 'confirmPassword'];
            const errors = ['currentPasswordError', 'newPasswordError', 'confirmPasswordError'];
            
            inputs.forEach(inputId => {
                document.getElementById(inputId).classList.remove('is-invalid');
            });
            
            errors.forEach(errorId => {
                const error = document.getElementById(errorId);
                error.textContent = '';
                error.style.display = 'none';
            });
        }
        
        // Real-time validation for new password
        document.getElementById('newPassword').addEventListener('input', function() {
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
        
        // Real-time validation for confirm password
        document.getElementById('confirmPassword').addEventListener('input', function() {
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