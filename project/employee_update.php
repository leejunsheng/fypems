<?php
include 'check_user_login.php';
$role1 = $_SESSION['role'];
$username = $_SESSION['login'];
$uid = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Employee Profile</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>


<body>
    <?php include 'topnav.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php
                // get passed parameter value, in this case, the record ID
                // isset() is a PHP function used to verify if a value is there or not
                $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die('ERROR: Record ID not found.');

                //include database connection
                include 'config/database.php';

                $action = isset($_GET['action']) ? $_GET['action'] : "";

                // if it was redirected from delete.php
                if ($action == 'deleted') {
                    echo "<div class='alert alert-success'>Record was deleted.</div>";
                }

                // read current record's data
                try {
                    // prepare select query
                    $query = "SELECT * FROM employee WHERE user_id = ? LIMIT 0,1";
                    $stmt = $con->prepare($query);

                    // this is the first question mark
                    $stmt->bindParam(1, $user_id);

                    // execute our query
                    $stmt->execute();

                    // store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    // values to fill up our form
                    extract($row);
                }

                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
                ?>

                <?php
                // check if form was submitted
                if ($_POST) {
                    $firstname = $_POST['firstname'];
                    $lastname = $_POST['lastname'];
                    $department = $_POST['department'];
                    $datebirth = $_POST['datebirth'];
                    $today = date("Ymd");
                    $date1 = date_create($datebirth);
                    $date2 = date_create($today);
                    $diff = date_diff($date1, $date2);

                    $image = !empty($_FILES["image"]["name"])
                        ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                        : htmlspecialchars($image, ENT_QUOTES);

                    $error_msg = "";

                    if ($firstname == "") {
                        $error_msg .= "<div>Please enter your first name.</div>";
                    }

                    if ($lastname == "") {
                        $error_msg .= "<div>Please enter your last name.</div>";
                    }

                    if ($gender == "") {
                        $error_msg .= "<div>Please select your gender.</div>";
                    }

                    if ($datebirth == "") {
                        $error_msg .= "<div>Please select your date of birth.</div>";
                    }

                    if ($datebirth == "") {
                        $error_msg .= "<div>Please make sure birth date are not empty. </div>";
                    } elseif ($diff->format("%R%y") <= "18") {
                        $error_msg .= "<div> User need 18 years old and above. </div>";
                    }

                    if ($department == "") {
                        $error_msg .= "<div>Please make sure department are not empty.</div>";
                    }

                    if ($accstatus == "") {
                        $error_msg .= "<div>Please make sure account status are not empty.</div>";
                    }

                    // now, if image is not empty, try to upload the image
                    if ($_FILES["image"]["name"]) {
                        // upload to file to folder
                        $target_directory = "uploads/employee/";
                        $target_file = $target_directory . $image;
                        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                        // make sure that file is a real image
                        $check = getimagesize($_FILES["image"]["tmp_name"]);
                        if ($check === false) {
                            // submitted file is an image
                            $error_msg .= "<div>Submitted file is not an image.</div>";
                        }

                        // make sure certain file types are allowed
                        $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                        if (!in_array($file_type, $allowed_file_types)) {
                            $error_msg .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                        }

                        // make sure file does not exist
                        if (file_exists($target_file)) {
                            $error_msg .= "<div>Image already exists. Try to change file name.</div>";
                        }

                        // make sure submitted file is not too large, can't be larger than 1 MB
                        if ($_FILES['image']['size'] > (1024000)) {
                            $error_msg .= "<div>Image must be less than 1 MB in size.</div>";
                        }

                        // make sure the 'uploads' folder exists
                        // if not, create it
                        if (!is_dir($target_directory)) {
                            mkdir($target_directory, 0777, true);
                        }

                        // if $file_upload_error_messages is still empty
                        if (empty($error_msg)) {
                            // it means there are no errors, so try to upload the file
                            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                                // it means photo was uploaded
                                echo "<div>";
                                $error_msg .= "<div>Unable to upload photo.</div>";
                                $error_msg .= "<div>Update the record to upload photo.</div>";
                                echo "</div>";
                            }
                        }
                    } elseif (empty($image)) {
                        $image = "profile_default.jpg";
                    }

                    if (isset($_POST['delete'])) {
                        $image = htmlspecialchars(strip_tags($image));

                        $image = !empty($_FILES["image"]["name"])
                            ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                            : "";
                        $target_directory = "uploads/employee/";
                        $target_file = $target_directory . $image;
                        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                        unlink("uploads/employee/" . $row['image']);
                        $query = "UPDATE employee
                                SET image=:image WHERE user_id = :user_id";
                        // prepare query for excecution
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(':image', $image);
                        $stmt->bindParam(':user_id', $user_id);
                        // Execute the query
                        $stmt->execute();

                        $error_msg .= "<div>Image delete successful, Please click update button.</div>";
                    }

                    if (!empty($error_msg)) {
                        echo "<div class='alert alert-danger'>{$error_msg}</div>";
                    } else {
                        // include database connection
                        include 'config/database.php';
                        try {
                            // write update query
                            // in this case, it seemed like we have so many fields to pass and
                            // it is better to label them and not use question marks
                            $query = "UPDATE employee SET username=:username, image=:image, firstname=:firstname, lastname=:lastname, gender=:gender, datebirth=:datebirth, accstatus=:accstatus, department=:department, leave_bal=:leave_bal WHERE user_id = :user_id";
                            // prepare query for excecution
                            $stmt = $con->prepare($query);

                            // posted values
                            $image = htmlspecialchars(strip_tags($image));
                            $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                            $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                            $datebirth = htmlspecialchars(strip_tags($_POST['datebirth']));
                            $department = htmlspecialchars(strip_tags($_POST['department']));

                            if ($role1) {
                                $leave_bal = htmlspecialchars(strip_tags($_POST['leave_bal']));
                                $accstatus = htmlspecialchars(strip_tags($_POST['accstatus']));
                            }

                            // bind the parameters
                            $stmt->bindParam(':username', $username);
                            $stmt->bindParam(':image', $image);
                            $stmt->bindParam(':firstname', $firstname);
                            $stmt->bindParam(':lastname', $lastname);
                            $stmt->bindParam(':gender', $gender);
                            $stmt->bindParam(':datebirth', $datebirth);
                            $stmt->bindParam(':user_id', $user_id);
                            $stmt->bindParam(':department', $department);
                            $stmt->bindParam(':accstatus', $accstatus);
                            $stmt->bindParam(':leave_bal', $leave_bal);


                            // Execute the query
                            if ($stmt->execute()) {

                             
                                //echo "<div class='alert alert-success'>Record was updated.</div>";
                                header("Location: employee_read.php?update={$user_id}");
                                exit(); // or die();
                                
                            } else {
                                echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                            }
                        }

                        // show errors
                        catch (PDOException $exception) {
                            die('ERROR: ' . $exception->getMessage());
                        }
                    }
                } ?>


                <div class="card">

                    <div class="card-header">
                        <h4>Update Employee Profile</h4>
                    </div>


                    <!--we have our html form here where new record information can be updated-->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?user_id={$user_id}"); ?>" method="post" enctype="multipart/form-data">
                        <table class='table table-hover table-responsive table-bordered'>
                            <tr>
                                <td>Username</td>
                                <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' disabled /></td>
                            </tr>
                            <tr>
                                <td>Image</td>
                                <td>
                                    <div><img src="uploads/employee/<?php echo htmlspecialchars($image, ENT_QUOTES);  ?>" class="w-25"></div>
                                    <div><input type="file" name="image" value="<?php echo htmlspecialchars($image, ENT_QUOTES);  ?>" /></div>


                                    <?php
                                    if ($image != "profile_default.jpg") {
                                        echo "<input type='submit' value='Delete Image' name='delete' class='btn btn-danger mt-2' />";
                                    }
                                    ?>
                                </td>
                            </tr>

                            <td>Department</td>
                            <td>
                                <select class="form-control w-100" id="department" name="department">
                                    <?php
                                    $departments = array("IT", "HR", "Account", "Marketting");

                                    foreach ($departments as $dept) {
                                        $selected = ($department === $dept) ? "selected='selected'" : "";
                                        echo "<option value='$dept' $selected>$dept</option>";
                                    }
                                    ?>
                                </select>

                            </td>

                            <tr>
                                <td>First Name</td>
                                <td><input type='text' name='firstname' value="<?php echo htmlspecialchars($firstname, ENT_QUOTES);  ?>" class='form-control' /></td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td><input type='text' name='lastname' value="<?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?>" class='form-control' /></td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td><input type='text' name='gender' value="<?php echo htmlspecialchars($gender, ENT_QUOTES);  ?>" class='form-control' disabled /></td>
                            </tr>

                            <tr>
                                <td>Date Of Birth</td>
                                <td><input type='date' name='datebirth' value="<?php echo htmlspecialchars($datebirth, ENT_QUOTES);  ?>" /></td>
                            </tr>

                            <tr>
                                <td>Leave Balance</td>
                                <td>
                                    <?php if ($role1 == 1) : ?>
                                        <!-- Admin can edit leave balance -->
                                        <input type='text' name='leave_bal' value="<?php echo htmlspecialchars($leave_bal, ENT_QUOTES); ?>" class='form-control' />
                                    <?php else : ?>
                                        <!-- User can only view leave balance -->

                                        <input type="text" name="leave_bal" value="<?php echo htmlspecialchars($leave_bal, ENT_QUOTES); ?>" class='form-control' disabled>
                                    <?php endif; ?>
                                </td>
                            </tr>


                            <tr>
                                <td>Account Status</td>
                                <td>
                                    <?php if ($role1 == 1) : ?>
                                        <!-- Admin can modify account status -->
                                        <input class="form-check-input" type="radio" name='accstatus' value="active" <?php if ($accstatus == 'active') echo 'checked'; ?>>
                                        <label class="form-check-label" for="active">
                                            Active
                                        </label>

                                        <input class="form-check-input" type="radio" name='accstatus' value="inactive" <?php if ($accstatus == 'inactive') echo 'checked'; ?>>
                                        <label class="form-check-label" for="inactive">
                                            Inactive
                                        </label>

                                    <?php else : ?>
                                        <input type="text" name="accstatus" value="<?php echo $accstatus; ?>" class='form-control' disabled>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <td></td>
                            <td>
                                <input type='submit' value='Update' class='btn btn-primary' />

                                <?php if ($role1 == 1) : ?>
                                    <!-- If role is 1, show all buttons -->
                                    <a href='employee_read.php' class='btn btn-secondary '><i class='fa-solid fa-circle-arrow-left'></i> Back to read employee </a>
                                    <?php echo "<a href='employee_delete.php?user_id={$user_id}' onclick='delete_employee({$user_id});'  class='btn btn-danger'>Delete</a>"; ?>
                                <?php elseif ($role1 == 0) : ?>
                                    <!-- If role is 0, show only the "Edit" button -->
                                    <a href='employee_read_one.php?user_id=<?php echo $user_id; ?>' class='btn btn-secondary '><i class='fa-solid fa-circle-arrow-left'></i> Back to Read Detail </a>
                                <?php endif; ?>

                            </td>
                            </tr>
                        </table>
                    </form>

                </div>
                <!-- end .container -->
            </div>


            <?php include 'script.php'; ?>
</body>

</html>