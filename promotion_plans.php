<?php
include "./navbar_dash.php";
?>
<link rel="stylesheet" href="vendor/css/pramotion.css">

<div id="main-container">
    <div class="container">
        <h1>Choose your Campaign Plans</h1>

        <section class="boxes m-1 row ">

            <?php $query = "SELECT * FROM campaign_package";
            $result = mysqli_query($conn, $query);
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-4 my-2 ">
                    <div class="box blue">

                        <div class="box-content">
                            <h2>
                                <?= $row["name"] ?>
                            </h2>
                            <h4>
                                <?= $row["price"] ?>
                            </h4>
                            <h5>
                                <?= $row["description"] ?>
                            </h5>
                            <button class="button" data-bs-toggle="modal"
                                data-bs-target="#exampleModal<?= $row["package_id"] ?>">
                                <span>Continue</span><i class="material-icons"></i>
                            </button>
                        </div>
                    </div>
                </div>


                <?php
                $user_id = $_SESSION["user_id"];

                if ($user_id) {
                    $q = "SELECT * FROM blog_posts WHERE user_id=$user_id";
                    $rq = mysqli_query($conn, $q);
                }
                ?>

                <div class="modal fade text-dark" id="exampleModal<?= $row["package_id"] ?>" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">
                                    Select post!
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row m-0">
                                    <?php
                                    while ($data = mysqli_fetch_assoc($rq)) {
                                        if ($data["comment_count"] == null) {
                                            $data["comment_count"] = 0;
                                        }
                                        ?>
                                        <?php
                                        $queryArr = array(
                                            "post_id" => $data["post_id"],
                                            "user_id" => $user_id,
                                            "package_id" => $row["package_id"]
                                        );

                                        $queryObj = json_encode($queryArr);

                                        $passed_string = base64_encode($queryObj);
                                        ?>
                                        <div class="col-4">
                                            <label for="radio-card-<?= $data['post_id'] ?><?= $row["package_id"] ?>"
                                                class="radio-card">
                                                <input type="radio" value="<?= $passed_string ?>" name="radio-card"
                                                    id="radio-card-<?= $data['post_id'] ?><?= $row["package_id"] ?>">
                                                <div class="card-content-wrapper">
                                                    <span class="check-icon">
                                                        <i class="bi bi-check-lg"></i>
                                                    </span>
                                                    <div class="card-content">
                                                        <img src="<?= $data["image"] ?>"
                                                            onerror="this.src='assets/site_logo.jpg'" alt="">
                                                        <h4>
                                                            <?= $data["title"] ?>
                                                        </h4>
                                                        <h5><i class="bi bi-heart-fill"></i>
                                                            <?= $data["like_count"] ?>
                                                            <i class="bi bi-chat-dots"></i>
                                                            <?= $data["comment_count"] ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="myFuncCall()">Select</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php

            }
            ?>
        </section>
    </div>
</div>

<script>
    function myFuncCall() {
        console.log($('input[name="radio-card"]:checked').val());
        if ($('input[name="radio-card"]:checked').val()) {
            window.location.href = `add_campaign.php?data=${$('input[name="radio-card"]:checked').val()}`
        }
    }
</script>