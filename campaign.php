<?php
include "./navbar_dash.php";
$user_id = $_SESSION["user_id"];
if (isset($_POST["submit"]) && isset($_POST["campaign_id"]) && isset($_POST["status"])) {
    // echo "submit";
  
    $campaign_id = $_POST["campaign_id"];
    $status = $_POST["status"];
  
    if (
      $status
    ) {
      $query = "update  campaigns set  status = '$status' where campaign_id = '$campaign_id'";
      // echo $query;
      $runquery = mysqli_query($conn, $query);
      if ($runquery) {
        $message[] = array(
          'type' => 'Campaign Update',
          'message' => 'Campaign Update successfully',
          'icon' => 'success'
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
  
  include "./alert_message.php";
?>

<div id="main-container">
    <h2 class="text-dark text-center">Campaign Manager</h2>
    <section class="section dashboard container">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="text-end">
                        <button class="btn btn-outline-success"><a href="./promotion_plans.php"> Add Campaign</a></button>
                    </div>
                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th scope="col">Sr No.</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Total Amount</th>
                                    <th scope="col">Change Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $query = "SELECT * FROM campaigns where user_id =$user_id ";
                                $result = mysqli_query($conn, $query);
                                $i = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <th scope='row'>
                                            <?= $i ?>
                                        </th>
                                        <td>
                                            <?= $row["name"] ?>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $row["status"];
                                            if ($status == 'pending') {
                                                echo "
                          <span class='badge bg-warning'>$status</span>
                        ";
                                            } elseif ($status == 'running') {
                                                echo "
                          <span class='badge bg-success'>$status</span>
                        ";
                                            } else {
                                                echo "
                          <span class='badge bg-danger'>$status</span>
                        ";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?= $row["start_date"] ?>
                                        </td>
                                        <td>
                                            <?= $row["end_date"] ?>
                                        </td>
                                        <td>
                                            &#8377;
                                            <?= $row["total_amount"] ?>
                                        </td>
                                        <td>

                                            <button class='btn btn-primary' type="button" data-bs-toggle='modal' data-bs-target='#edit-campaign-modal<?= $i ?>'><i class='bi bi-pencil'></i></button>

                                        </td>
                                    </tr>
                                    <div class='modal fade' id='edit-campaign-modal<?= $i ?>' tabindex='-1' style='display: none;' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title'>Update Campaign Status(
                                                        <?= $row["name"] ?>)
                                                    </h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <form method="POST" id='edit-form'>
                                                    <div class='modal-body'>
                                                        <input class='form-control' value="<?= $row["campaign_id"] ?>" type='hidden' name='campaign_id'>
                                                        <div class='form-group row '>
                                                            <div class="col-4">
                                                                <label for='status'>campaign Status</label>
                                                            </div>
                                                            <div class="col-8">
                                                                <select name="status"   class="form-control" id="status" value="<?= $row["status"] ?>">
                                                                    <option value="running">Running</option>
                                                                    <option value="close">Close</option>
                                                                </select>
                                                            </div>
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
                    </div>
                    <!-- End Table with stripped rows -->
                </div>
            </div> <!-- Left side columns -->
        </div>
    </section>
</div>

<script src="vendor/js/ajex-call.js"></script>
<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>