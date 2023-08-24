<?php
session_start();
if (isset($_SESSION["login"])) {
    include 'topnav.php';
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Create Employee</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>

<body>
    <section class="h-100 pt-3">
        <div class="container min-vh-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-lg-9 col-xl-7">

                    <!-- container -->
                    <!-- PHP insert code will be here -->
                    <?php
                    $user_name = $image = $firstname = $lastname =  $gender = $datebirth = $role = $accstatus = $department = $leave_bal = "";

                    if ($_POST) {
                        $user_name = $_POST['username'];
                        $pass_word = md5($_POST['password']);
                        $comfirm_pasword = md5($_POST['comfirm_password']);
                        $firstname = $_POST['firstname'];
                        $lastname = $_POST['lastname'];
                        $gender =  !empty($_POST['gender']) ? $_POST['gender'] : "";
                        $datebirth = $_POST['datebirth'];
                        $leave_bal = $_POST['leave_bal'];
                        $role = $_POST['role'];
                        $department = !empty($_POST['department']) ? $_POST['department'] : "";
                        $accstatus = !empty($_POST['accstatus']) ? $_POST['accstatus'] : "";


                        $today = date("Ymd");
                        $date1 = date_create($datebirth);
                        $date2 = date_create($today);
                        $diff = date_diff($date1, $date2);

                        // new 'image' field
                        $image = !empty($_FILES["image"]["name"])
                            ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                            : "";

                        $error_msg = "";

                        if ($user_name == "") {
                            $error_msg .= "<div >Please make sure username are not empty.</div>";
                        } elseif (strlen($user_name) < 6) {
                            $error_msg .= "<div >Please make sure uername not less than 6 character.</div>";
                        } elseif (preg_match('/[" "]/', $user_name)) {
                            $error_msg .= "<div >Please make sure uername did not conatain space.</div>";
                        }

                        if ($pass_word == md5("")) {
                            $error_msg .= "<div >Please make sure password are not empty.</div>";
                        } elseif (strlen($pass_word) < 8) {
                            $error_msg .= "<div >Please make sure password less than 8 character.</div>";
                        } elseif (!preg_match('/[a-z]/', $pass_word)) {
                            $error_msg .= "<div >Please make sure password combine capital a-z.</div>";
                        } elseif (!preg_match('/[0-9]/', $pass_word)) {
                            $error_msg .= "<div >Please make sure password combine 0-9.</div>";
                        }

                        if ($comfirm_pasword != $pass_word) {
                            $error_msg .= "<div >Please make sure comfirm_password and password are same.</div>";
                        }

                        if ($firstname == "") {
                            $error_msg .= "<div >Please make sure firstname are not empty.</div>";
                        }

                        if ($lastname == "") {
                            $error_msg .= "<div >Please make sure lastname are not empty.</div>";
                        }

                        if ($datebirth == "") {
                            $error_msg .= "<div >Please make sure birth date are not empty.</div>";
                        } elseif ($diff->format("%R%y") <= "18") {
                            $error_msg .= "<div >User need 18 years old and above.</div>";
                        }

                        if (empty($gender)) {
                            $error_msg .= "<div>Please select your gender.</div>";
                        }

                        if (empty($department)) {
                            $error_msg .= "<div>Please select your department.</div>";
                        }

                        if (empty($accstatus)) {
                            $error_msg .= "<div>Please select your account status.</div>";
                        }

                        if (empty($department)) {
                            $error_msg .= "<div>Please select your department.</div>";
                        }

                        // now, if image is not empty, try to upload the image
                        if ($image) {

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
                                    echo "<div >";
                                    $error_msg .= "<div>Unable to upload photo.</div>";
                                    $error_msg .= "<div>Update the record to upload photo.</div>";
                                    echo "</div>";
                                }
                            }
                        } elseif (empty($image)) {
                            $image = "profile_default.jpg";
                        }

                        if (!empty($error_msg)) {
                            echo "<div class='alert alert-danger'>{$error_msg}</div>";
                        } else {
                            // include database connection
                            include 'config/database.php';
                            try {
                                // insert query
                                $query = "INSERT INTO employee SET username=:username, image=:image, password=:password, firstname=:firstname, lastname=:lastname,gender=:gender,datebirth=:datebirth,registration_dt=:registration_dt,role=:role,department=:department,accstatus=:accstatus,leave_bal=:leave_bal";

                                // prepare query for execution
                                $stmt = $con->prepare($query);

                                // bind the parameters
                                $stmt->bindParam(':username', $user_name);
                                $stmt->bindParam(':image', $image);
                                $stmt->bindParam(':password', $pass_word);
                                $stmt->bindParam(':firstname', $firstname);
                                $stmt->bindParam(':lastname', $lastname);
                                $stmt->bindParam(':gender', $gender);
                                $stmt->bindParam(':datebirth', $datebirth);
                                $registration_dt = date('Y-m-d H:i:s'); // get the current date and time
                                $stmt->bindParam(':registration_dt', $registration_dt);
                                $stmt->bindParam(':role', $role);
                                $stmt->bindParam(':department', $department);
                                $stmt->bindParam(':leave_bal', $leave_bal);
                                $stmt->bindParam(':accstatus', $accstatus);

                                // Execute the query
                                if ($stmt->execute()) {
                                    echo "<div class='alert alert-success'>Employee Create Successful.</div>";
                                } else {
                                    if (isset($_SESSION["login"])) {
                                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                                    } else {
                                        header("Location: login.php?action=fail");
                                    }
                                }
                            }
                            // show error
                            catch (PDOException $exception) {
                                die('ERROR: ' . $exception->getMessage());
                            }
                        }
                    }
                    ?>

                    <!-- html form here where the product information will be entered -->

                    <div class="card " style="border-radius: 15px;">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Registration Form</h3>
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                                <div>
                                    <div class="col-md-12 mb-4">
                                        <div class="form-outline">
                                            <input input type='text' name='username' value='<?php echo $user_name ?>' class="form-control form-control-lg" placeholder="Username" />
                                            <label class="form-label" for="firstName">Username</label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="col-md-12 mb-4">
                                        <div class="form-outline">
                                            <input input type='file' name='image' value='<?php echo $image ?>' class="form-control form-control-lg" />
                                            <label class="form-label">Image</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline">
                                            <input type='text' name='firstname' value='<?php echo  $firstname ?>' class="form-control form-control-lg" placeholder="First Name" />
                                            <label class="form-label" for="firstName">First Name</label>
                                        </div>

                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline">
                                            <input type='text' name='lastname' value='<?php echo $lastname ?>' class="form-control form-control-lg" placeholder="Last Name" />
                                            <label class="form-label" for="lastName">Last Name</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline">
                                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter password" />
                                            <label class="form-label" for="password">Password</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline">
                                            <input type="password" name="comfirm_password" class="form-control form-control-lg" placeholder="Confirm password" />
                                            <label class="form-label" for="confirmpassword">Confirm Password</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <select class="form-control w-100" id="department" name="department">
                                            <option selected="true" Select Department" disabled>Select Department</option>
                                            <option value="IT">IT</option>
                                            <option value="HR">HR</option>
                                            <option value="Account">Account</option>
                                            <option value="Marketting">Marketting</option>

                                        </select>
                                        <label for="department" class="form-label">Department</label>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="form-outline datepicker w-100">
                                            <input type='date' name='datebirth' value='<?php echo $datebirth ?>' class="form-control form-control-lg" />
                                            <label for="birthdayDate" class="form-label">Birthday</label>
                                        </div </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <h6 class="mb-2 pb-1">Gender: </h6>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name='gender' value="Male">
                                                <label class="form-check-label" for="gender">
                                                    Male
                                                </label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name='gender' value="Female">
                                                <label class="form-check-label" for="gender">
                                                    Female
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <h6 class="mb-2 pb-1">Account Status: </h6>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name='accstatus' value="active">
                                                <label class="form-check-label" for="active">
                                                    Active
                                                </label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name='accstatus' value="inactive">
                                                <label class="form-check-label" for="inactive">
                                                    Inactive
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <h6 class="mb-2 pb-1">Role: </h6>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="role" value="1">
                                                <label class="form-check-label" for="0">Admin</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="role" value="0">
                                                <label class="form-check-label" for="1">Employee</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
    <h6 class="mb-2 pb-1">Leave Balance </h6>
    <select class="form-select" name="leave_bal">
        <?php
        for ($i = 1; $i <= 20; $i++) {
            echo "<option value='$i'>$i</option>";
        }
        ?>
    </select>
</div>



                                        <div class="row justify-content-center">
                                            <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                <button class="btn btn-outline-dark btn-lg px-5" type="submit" value="submit">Register</button>
                                            </div>
                                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'script.php'; ?>

</body>

</html>