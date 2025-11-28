<?php
include "../connection.php";
include "./sidebar.php";
?>

<main class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 py-3">
            <h4 class="mb-0">All Users</h4>
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
                                            src="<?= "." . $row["image"] ?>"
                                            onerror="this.src='../assets/default-profile.png'" style="object-fit: cover;"
                                            alt="<?= $row["name"] ?>">
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
</main>