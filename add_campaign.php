<?php

require "./navbar_dash.php";
if (isset($_POST['submit']) && isset($_POST["package_id"]) && isset($_POST["post_id"]) && isset($_POST["user_id"]) && isset($_POST["start_date"]) && isset($_POST["end_date"]) && isset($_POST["name"]) && isset($_POST["total_amount"])) {
  $package_id = $_POST["package_id"];
  $post_id = $_POST["post_id"];
  $user_id = $_POST["user_id"];
  $start_date = $_POST["start_date"];
  $end_date = $_POST["end_date"];
  $name = $_POST["name"];
  $total_amount = $_POST["total_amount"];
  if ($package_id && $post_id && $user_id && $start_date && $end_date && $name && $total_amount) {
    $query = "SELECT COUNT(*) FROM campaigns WHERE post_id = $post_id AND user_id = $user_id AND status IN ('pending', 'running')";
    $result = mysqli_query($conn, $query);

    // print_r(mysqli_fetch_row($result));

    if ($result) {
      $campaignCount = mysqli_fetch_row($result);
      // print_r($campaignCount[0]);

      if ($campaignCount[0] > 0) {
        $message[] = [
          'icon' => 'warning',
          'type' => 'Campaigns',
          'message' => 'Campaigns is already added',
          'redirection' => 'promotion_plans.php',
        ];
      } else {
        $insertQuery = "INSERT INTO campaigns (package_id, post_id, user_id, start_date, end_date, name, total_amount) VALUES (
                $package_id,
                $post_id,
                $user_id,
                '$start_date',
                '$end_date',
                '$name',
                $total_amount
            )";

        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
          $message[] = [
            'icon' => 'success',
            'type' => 'New campaign',
            'message' => 'Campaign add successfully.',
            'redirection' => 'dashboard.php',
          ];
        } else {
          $message[] = [
            'icon' => 'error',
            'type' => 'Error',
            'message' => 'Error while inserting campaign: ' . mysqli_error($conn)
          ];
        }
      }
    } else {
      $message[] = [
        'icon' => 'error',
        'type' => 'Error',
        'message' => 'Error while checking existing campaigns.'
      ];
    }
  } else {
    $message[] = [
      'icon' => 'error',
      'type' => 'Error',
      'message' => 'Enter valid Form Information'
    ];
  }
}
if (isset($_GET['data'])) {
  $data = $_GET['data'];
  $decodeData = base64_decode($data);
  $jsonData = json_decode($decodeData);
  if (isset($jsonData)) {
    $post_id = $jsonData->post_id;
    $user_id = $jsonData->user_id;
    $package_id = $jsonData->package_id;
    $q = "INSERT INTO campaign(user_id,post_id,pakage_id,start_date,end_date) VALUE ($user_id,$post_id,$package_id,'val','val')";
  } else {
    header("location:dashboard.php");
  }
  // echo $post_id . "</br>";
  // echo $user_id . "</br>";
  // echo $package_id . "</br>";

  // echo $q;


} else {
  header("location:dashboard.php");
}
include "./alert_message.php";

?>


<div>
  <h1 class="text-center mt-2">
    New campaign
  </h1>
  <div class="row m-0">
    <div class="col-md-6">
      <section class="boxes row justify-content-center  m-0 pt-0 ">
        <h4 class="text-center">Selected Plan</h4>
        <div class="col-8">
          <div class="box blue" style="min-height: auto;">
            <div class="number-bg">6</div>
            <div class="box-content">
              <?php
              $query = "Select * from campaign_package where package_id = $package_id ";
              $runquery = mysqli_query($conn, $query);
              $row = mysqli_fetch_assoc($runquery);
              $total_days = $row['total_days'];
              $total_amount = $row['price'];
              ?>
              <h2>
                <?= $row['name'] ?>
              </h2>
              <h4>
                <?= $row['price'] ?>
                </h2>
              </h4>
              <h5>
                <?= $row['description'] ?>
                </h2>
                </h4>
              </h5>
            </div>
          </div>
        </div>
      </section>
    </div>
    <div class="col-md-6">
      <section class="boxes row justify-content-center  m-0 pt-0 ">
        <h4 class="text-center">Selected Post</h4>
        <div class="col-8">
          <div class="box blue" style="min-height: auto;">
            <div class="number-bg">6</div>
            <?php
            $query = "Select * from blog_posts where post_id = $post_id ";
            $runquery = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($runquery);
            ?>
            <div class="box-content">
              <div class="card-content">
                <img src="<?= $row['image'] ?>" onerror="this.src='assets/site_logo.jpg'" class="m-2" alt="" style="
                    height: 150px;
                    border-radius: 16px;
                ">
                <h4>
                  <?= $row['title'] ?>
                </h4>
                <h5><i class="bi bi-heart-fill"></i>
                  <?= $row['like_count'] ?> <i class="bi bi-chat-dots"></i> 0
                </h5>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
  <div class="container">
    <form method="post" id='edit-form'>
      <div class="row g-3">
        <div class="col-md-6">
          <input class='form-control' type='hidden' value="<?= $package_id ?>" name='package_id'>
          <input class='form-control' type='hidden' value="<?= $post_id ?>" name='post_id'>
          <input class='form-control' type='hidden' value="<?= $user_id ?>" name='user_id'>
          <label for="start_date" class="form-label">Start</label>
          <input type="datetime-local" class="form-control"  onchange="endDateChange(<?= $total_days ?>)" id="start_date"
            name="start_date" required>
        </div>
        <div class="col-md-6">
          <label for="end_date" class="form-label">End</label>
          <input type="datetime-local" class="form-control" readonly id="end_date" name="end_date" required>
        </div>
        <div class="col-md-6">
          <label for="campaign_name" class="form-label">Campaign name</label>
          <input type="text" class="form-control" id="campaign_name" name="name" required>
        </div>
        <div class="col-md-6">
          <label for="total_amount" class="form-label">Total Amount</label>
          <input type="text" class="form-control" readonly value="<?= $total_amount * $total_days ?>" id="total_amount"
            name="total_amount" required>
        </div>
        <div class="col-12">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <button name="submit" value="true" type="submit" class="btn btn-dark w-100 fw-bold">Send</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

</div>

<script>
  function endDateChange(days) {
    if ($("#start_date").val()) {
      const date = new Date($("#start_date").val());
      // console.log(date);
      const result = addDays(date, days);
      result.setMinutes(result.getMinutes() - result.getTimezoneOffset());

      const formattedDate = result.toISOString().slice(0, 16);
      $("#end_date").val(formattedDate);
      // console.log(formattedDate);
    }
  }

  function addDays(date, days) {
    date.setDate(date.getDate() + days);
    return date;
  }
</script>