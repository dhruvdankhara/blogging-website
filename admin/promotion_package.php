<?php
include "../connection.php";
include "./sidebar.php";
if (isset($_POST["submit"]) && isset($_POST["name"]) && isset($_POST["package_id"]) && isset($_POST["price"]) && isset($_POST["description"])) {

    $package_id = $_POST["package_id"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    if (
        $name &&
        $price &&
        $description
    ) {
        $query = "update campaign_package set  name = '$name' , 	price = '$price' , description='$description'   where package_id = '$package_id'";
        $runquery = mysqli_query($conn, $query);
        if ($runquery) {
            $message[] = array(
                'type' => 'Package Update',
                'message' => 'Package Update successfully',
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
if (isset($_POST["insert"]) && isset($_POST["name"]) && isset($_POST["price"]) && isset($_POST["description"])) {

    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    if (
        $name &&
        $price &&
        $description
    ) {
        $query = "insert into campaign_package  (name , price , description) values('$name' , '$price' , '$description')";
        $runquery = mysqli_query($conn, $query);
        if ($runquery) {
            $message[] = array(
                'type' => 'Add Package',
                'message' => 'Package Insert successfully',
                'icon' => 'success'
              );
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
if (isset($_POST["delete"]) && isset($_POST["package_id"])) {
    $package_id = $_POST["package_id"];
    $query = "delete from campaign_package  where package_id = '$package_id'";
    $runquery = mysqli_query($conn, $query);
    if ($runquery) {
        $message[] = array(
            'type' => 'Package Delete',
            'message' => 'Package delete successfully!',
            'icon' => 'success'
          );
        $message[] = 'Package delete successfully!';
    }
}


include "../alert_message.php";

?>

<main id="main" class="main">
    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="align-items-center d-flex justify-content-between">
                        <h3 class="my-3 float-start">Campaign Package Information</h3>
                        <div class="btn btn-primary float-end" data-bs-toggle='modal' data-bs-target='#edit-package-modal'>Add
                            Package</div>
                    </div>
                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th scope="col">Sr No.</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $query = "SELECT * FROM campaign_package";
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
                                            <?= $row["price"] ?>
                                        </td>
                                        <td>
                                            <?= $row["description"] ?>
                                        </td>
                                        <td>
                                            <form method="post" id='edit-form' class="m-0">
                                                <input class='form-control' type='hidden' value="<?= $row["package_id"] ?>" name='package_id'>
                                                <button class='btn btn-primary' type="button" data-bs-toggle='modal' data-bs-target='#edit-package-modal<?= $i ?>'><i class='bi bi-pencil'></i></button>
                                                <button class='btn btn-danger' type="submit" name="delete"><i class='bi bi-trash'></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <div class='modal fade' id='edit-package-modal<?= $i ?>' tabindex='-1' style='display: none;' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title'>Update Package(
                                                        <?= $row["name"] ?>)
                                                    </h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <form method="post" id='edit-form'>
                                                    <div class='modal-body'>
                                                        <input class='form-control' value="<?= $row["package_id"] ?>" type='hidden' name='package_id'>
                                                        <div class='form-group row '>
                                                            <div class="col-4">
                                                                <label for='full_name'>Package Name</label>
                                                            </div>
                                                            <div class="col-8">
                                                                <input class='form-control' value="<?= $row["name"] ?>" type='text' name='name'>
                                                            </div>
                                                        </div>
                                                        <div class='form-group row '>
                                                            <div class="col-4">
                                                                <label for='Package_Price<?= $row["package_id"] ?>'>Package Price</label>
                                                            </div>
                                                            <div class="col-8">
                                                                <input class='form-control' id="Package_Price<?= $row["package_id"] ?>" value="<?= $row["price"] ?>" type='text' name='price'>
                                                            </div>
                                                        </div>
                                                        <div class='form-group row '>
                                                            <div class="col-4">
                                                                <label for='package_description<?= $row["package_id"] ?>'>Package Description</label>
                                                            </div>
                                                            <div class="col-8">
                                                                <input class='form-control' id="package_description<?= $row["package_id"] ?>" value="<?= $row["description"] ?>" type='text' name='description'>
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
                    
                </div>
            </div> 
        </div>
        <div class='modal fade' id='edit-package-modal' style='display: none;' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Add package</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <form method="post" id='edit-form'>
                        <div class='modal-body'>
                            
                            <div class='form-group row'>
                                <div class="col-4">
                                    <label for='package_Name'>Package Name</label>
                                </div>
                                <div class="col-8">
                                    <input class='form-control' id="package_Name" type='text' name='name'>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <div class="col-4">
                                    <label for='package_price'>Package Price</label>
                                </div>
                                <div class="col-8">
                                    <input class='form-control' id="package_price" type='text' name='price'>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <div class="col-4">
                                    <label for='package_description'>Package Description</label>
                                </div>
                                <div class="col-8">
                                    <input class='form-control' id="package_description" type='text' name='description'>
                                </div>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                            <button type='submit' name="insert" class='btn btn-primary'>Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>