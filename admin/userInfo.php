<?php
include "../connection.php";
include "./sidebar.php";
if (isset($_POST["submit"])) {
    if (isset($_POST["name"]) && isset($_POST["user_id"]) && isset($_POST["email"]) && isset($_POST["user_type"])) {

        $user_id = $_POST["user_id"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $user_type = $_POST["user_type"];
        if (
            $user_id &&
            $name &&
            $email &&
            $user_type
        ) {
            if ($_FILES["image"]["name"]) {

                $filename = $_FILES["image"]["name"];
                $tempname = $_FILES["image"]["tmp_name"];
                $folder = "./upload/profile/" . time() . $filename;
                $allowed_image_extension = array(
                    "png",
                    "jpg",
                    "jpeg"
                );
                $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $query = "select * from  Users where user_id = '$user_id' ";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);

                if (!in_array($file_extension, $allowed_image_extension)) {
                    $message[] = array(
                        'icon' => 'error',
                        'type' => 'Error',
                        'message' => 'Upload valid images. Only PNG and JPEG are allowed.'
                    );
                } else if (move_uploaded_file($tempname, "." . $folder)) {
                    $query = "update  Users set  name = '$name' , email = '$email' , user_type = '$user_type', image = '$folder'  where user_id = '$user_id' ";
                    $runquery = mysqli_query($conn, $query);
                    if ($runquery) {
                        $message[] = array(
                            'type' => 'User Update',
                            'message' => 'User Update successfully!',
                            'icon' => 'success'
                        );
                        if ($row['image']) {
                            $filenameRm = "." . $row['image'];
                            if (file_exists($filenameRm)) {
                                $status = unlink($filenameRm) ? 'The file ' . $filenameRm . ' has been deleted' : 'Error deleting ' . $filenameRm;
                            }
                        }
                    }
                } else {
                    $message[] = array(
                        'icon' => 'error',
                        'type' => 'Error',
                        'message' => 'Failed to upload image!'
                    );
                }
            } else {

                $query = "update  Users set  name = '$name' , email = '$email' , user_type = '$user_type'where user_id = '$user_id' ";
                $runquery = mysqli_query($conn, $query);
                if ($runquery) {
                    $message[] = array(
                        'type' => 'User Update',
                        'message' => 'User Update successfully!',
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
    } else {
        $message[] = array(
            'icon' => 'error',
            'type' => 'Error',
            'message' => 'Enter  valid  Form Information'
        );
    }
}
if (isset($_POST["delete"]) && isset($_POST["user_id"])) {
    $user_id = $_POST["user_id"];
    $query = "select * from  Users where user_id = '$user_id' ";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    // Delete all campaigns associated with this user's posts
    $delete_campaigns_query = "DELETE FROM campaigns WHERE post_id IN (SELECT post_id FROM blog_posts WHERE user_id = '$user_id')";
    mysqli_query($conn, $delete_campaigns_query);
    
    // Delete all comments on this user's posts (if exists)
    $delete_comments_query = "DELETE FROM comments WHERE post_id IN (SELECT post_id FROM blog_posts WHERE user_id = '$user_id')";
    mysqli_query($conn, $delete_comments_query);
    
    // Delete all comments made BY this user (if exists)
    $delete_user_comments_query = "DELETE FROM comments WHERE user_id = '$user_id'";
    mysqli_query($conn, $delete_user_comments_query);
    
    // Delete all likes on this user's posts (if exists)
    $delete_likes_query = "DELETE FROM likes WHERE post_id IN (SELECT post_id FROM blog_posts WHERE user_id = '$user_id')";
    mysqli_query($conn, $delete_likes_query);
    
    // Delete all likes made BY this user (if exists)
    $delete_user_likes_query = "DELETE FROM likes WHERE user_id = '$user_id'";
    mysqli_query($conn, $delete_user_likes_query);
    
    // Delete all saved posts related to this user's posts (if exists)
    $delete_saved_query = "DELETE FROM saved_posts WHERE post_id IN (SELECT post_id FROM blog_posts WHERE user_id = '$user_id')";
    mysqli_query($conn, $delete_saved_query);
    
    // Delete all saved posts by this user (if exists)
    $delete_user_saved_query = "DELETE FROM saved_posts WHERE user_id = '$user_id'";
    mysqli_query($conn, $delete_user_saved_query);
    
    // Delete all blog posts associated with this user
    $delete_posts_query = "DELETE FROM blog_posts WHERE user_id = '$user_id'";
    mysqli_query($conn, $delete_posts_query);
    
    // Step 9: Delete user's profile image
    if ($row['image']) {
        $filenameRm = "." . $row['image'];
        if (file_exists($filenameRm)) {
            $status = unlink($filenameRm) ? 'The file ' . $filenameRm . ' has been deleted' : 'Error deleting ' . $filenameRm;
            // echo $status;
        }
    }
    
    // Step 10: Now delete the user
    $query = "delete from Users  where user_id = '$user_id'";
    $runquery = mysqli_query($conn, $query);
    if ($runquery) {
        $message[] = array(
            'type' => 'User  Delete',
            'message' => 'User and all associated data deleted successfully!',
            'icon' => 'success'
        );
    }
}
include "../alert_message.php";

?>

<main class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Management</h1>
        <small class="text-muted">Manage system users</small>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 py-3">
            <h5 class="mb-0">All Users</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">Email</th>
                            <th scope="col">Type</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php
                            $query = "SELECT * FROM Users";
                            $result = mysqli_query($conn, $query);
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img class="rounded-circle me-3" height="40" width="40" 
                                                 src="<?= "." . $row["image"] ?>" onerror="this.src='../assets/default-profile.png'" 
                                                 style="object-fit: cover;" alt="<?= $row["name"] ?>">
                                            <div>
                                                <div class="fw-medium"><?= htmlspecialchars($row["name"]) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row["email"]) ?></td>
                                    <td>
                                        <?php if ($row["user_type"] == "admin"): ?>
                                            <span class="badge bg-primary">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Client</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" type="button" 
                                                    data-bs-toggle="modal" data-bs-target="#edit-user-modal<?= $i ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="user_id" value="<?= $row["user_id"] ?>">
                                                <button class="btn btn-sm btn-outline-danger" type="submit" name="delete" 
                                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit User Modal -->
                                <div class="modal fade" id="edit-user-modal<?= $i ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit User: <?= htmlspecialchars($row["name"]) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <input type="hidden" name="user_id" value="<?= $row["user_id"] ?>">
                                                    
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <label class="form-label">Full Name</label>
                                                            <input class="form-control" type="text" name="name" 
                                                                   value="<?= htmlspecialchars($row["name"]) ?>" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label">Email</label>
                                                            <input class="form-control" type="email" name="email" 
                                                                   value="<?= htmlspecialchars($row["email"]) ?>" required>
                                                        </div>

                                                        <div class="col-12">
                                                            <label class="form-label">Profile Image</label>
                                                            <input type="file" class="form-control" name="image" accept="image/*" 
                                                                   onchange="loadFile<?= $row['user_id'] ?>(event)">
                                                        </div>
                                                        <div class="col-12 text-center">
                                                            <img class="rounded border" id="output<?= $row["user_id"] ?>" 
                                                                 height="120" width="120" src="<?= "." . $row["image"] ?>" 
                                                                 style="object-fit: cover;" alt="Preview">
                                                            <p class="small text-muted mt-2">Current image</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="submit" class="btn btn-primary">
                                                        <i class="bi bi-check-lg me-1"></i>Save Changes
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    var loadFile<?= $row['user_id'] ?> = function(event) {
                                        var output = document.getElementById('output<?= $row["user_id"] ?>');
                                        output.src = URL.createObjectURL(event.target.files[0]);
                                        output.onload = function() {
                                            URL.revokeObjectURL(output.src);
                                        }
                                    };
                                </script>
                            <?php
                                $i++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>