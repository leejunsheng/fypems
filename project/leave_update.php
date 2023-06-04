<?php
// include database connection
include 'check_user_login.php';
$username = $_SESSION['login'];
$uid = $_SESSION['user_id']


?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Employee Profile</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'topnav.php'; ?>
    <section class="h-100 py-3">
        <div class="container h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-lg-9 col-xl-7">

                    <!-- container -->
                    <!-- PHP insert code will be here -->
                    <?php
                    $leave_id = isset($_GET['leave_id']) ? $_GET['leave_id'] : die('ERROR: Record ID not found.');

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
                        $query = "SELECT * FROM `leave` WHERE leave_id = ? LIMIT 0,1";
                        $stmt = $con->prepare($query);

                        // this is the first question mark
                        $stmt->bindParam(1, $leave_id);

                        // execute our query
                        $stmt->execute();

                        // store retrieved row to a variable
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        // values to fill up our form
                        $user_id = $row['user_id'];
                        $leave_type = $row['leave_type'];
                        $leave_category = $row['leave_category'];
                        $start_date = $row['start_date'];
                        $end_date = $row['end_date'];
                        $leave_date = $row['leave_date'];
                        $time_period = $row['time_period'];
                        $description = $row['description'];
                    }

                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }

                    if ($_POST) {
                        $leave_type = $_POST['leave-type'];
                        $leave_cat = $_POST['leave-cat'];
                        $start_date = $_POST['start-date'];
                        $end_date = $_POST['end-date'];
                        $leave_date = $_POST['leave-date'];
                        $time_period = isset($_POST['time-period']) ? $_POST['time-period'] : null;
                        $desc = $_POST['desc'];


                        if (empty($start_date)) {
                            $start_date = null;
                        }

                        if (empty($end_date)) {
                            $end_date = null;
                        }

                        if (empty($leave_date)) {
                            $leave_date = null;
                        }

                        if (empty($time_period)) {
                            $time_period = null;
                        }


                        $error_msg = "";
                        if ($leave_type == "") {
                            $error_msg .= "<div >Please make sure leave type are not empty.</div>";
                        }

                        if ($leave_cat == "") {
                            $error_msg .= "<div >Please make sure leave category are not empty.</div>";
                        }

                        if ($desc == "") {
                            $error_msg .= "<div >Please make sure leave description are not empty.</div>";
                        }

                        if (!empty($error_msg)) {
                            echo "<div class='alert alert-danger'>{$error_msg}</div>";
                        } else {
                            include 'config/database.php';
                            try {
                                // insert query
                                $query = "UPDATE `leave` (leave_type, leave_category, start_date, end_date, leave_date, time_period, description) VALUES (:leave_type, :leave_category, :start_date, :end_date, :leave_date, :time_period, :desc)";

                                // Prepare the statement
                                $stmt = $con->prepare($query);

                                // posted values
                                $leave_type = htmlspecialchars(strip_tags($_POST['leave-type']));
                                $leave_cat = htmlspecialchars(strip_tags($_POST['leave-cat']));
                                $start_date = htmlspecialchars(strip_tags($_POST['start-date']));
                                $end_date = htmlspecialchars(strip_tags($_POST['end-date']));
                                $leave_date = htmlspecialchars(strip_tags($_POST['leave-date']));
                                $time_period = isset($_POST['time-period']) ? $_POST['time-period'] : null;
                                $desc = htmlspecialchars(strip_tags($_POST['desc']));

                                // Bind parameters
                                $stmt->bindParam(':leave_type', $leave_type);
                                $stmt->bindParam(':leave_category', $leave_cat);
                                $stmt->bindParam(':start_date', $start_date);
                                $stmt->bindParam(':end_date', $end_date);
                                $stmt->bindParam(':leave_date', $leave_date);
                                $stmt->bindParam(':time_period', $time_period);
                                $stmt->bindParam(':desc', $desc);

                                // Execute the query
                                if ($stmt->execute()) {
                                    echo "<div class='alert alert-success'>Leave added successfully.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
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

                    <div class="card" style="border-radius: 15px; ">
                        <div class="card-body p-4 p-md-5 ">
                            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Apply Leave</h3>
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                                <div class="card-body ">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="leave-type">Leave Type <span class="text-danger">*</span></label>
                                        <div class="col-lg-6 mb-3">
                                            <select class="form-control" id="leave-type" name="leave-type">
                                                <option value="CL" <?php if ($leave_type == 'CL') echo 'selected'; ?>>CL</option>
                                                <option value="EL" <?php if ($leave_type == 'EL') echo 'selected'; ?>>EL</option>
                                                <option value="RH" <?php if ($leave_type == 'RH') echo 'selected'; ?>>RH</option>
                                                <option value="HPL" <?php if ($leave_type == 'HPL') echo 'selected'; ?>>HPL</option>
                                                <option value="CCL" <?php if ($leave_type == 'CCL') echo 'selected'; ?>>CCL</option>
                                                <option value="Others" <?php if ($leave_type == 'Others') echo 'selected'; ?>>Others</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label" for="leave-cat">Leave Category <span class="text-danger">*</span></label>
                                        <div class="col-lg-6">
                                            <select class="form-control" id="leave-cat" name="leave-cat" onchange="toggleFields(this.value);">
                                                <option value="Full Day" <?php if ($leave_category === 'Full Day') echo 'selected'; ?>>Full Day</option>
                                                <option value="Half Day" <?php if ($leave_category === 'Half Day') echo 'selected'; ?>>Half Day</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="fullday" style="display: none;" class="mb-3 ">
                                        <div class="form-group row ">
                                            <label class="col-lg-4 col-form-label" for="start-date">Leave Start Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" name="start-date" value="<?php echo htmlspecialchars($start_date, ENT_QUOTES);  ?>" placeholder="Select Leave Start Date">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="end-date">Leave End Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" name="end-date" value="<?php echo htmlspecialchars($end_date, ENT_QUOTES);  ?>" placeholder="Select Leave End Date">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="halfday" class="mb-3" style='display: none;'>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="leave-date">Leave Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" id="leave-date" name="leave-date" value="<?php echo htmlspecialchars($leave_date, ENT_QUOTES); ?>" placeholder="Select Leave Date">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="time-period">Time Period <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3">
                                                <select class="form-control" id="time-period" name="time-period">
                                                    <option selected disabled>Select Time Period</option>
                                                    <option value="Morning" <?php echo ($time_period === "Morning") ? "selected" : ""; ?>>Morning</option>
                                                    <option value="Afternoon" <?php echo ($time_period === "Afternoon") ? "selected" : ""; ?>>Afternoon</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label" for="desc">Remarks <span class="text-danger">*</span></label>
                                        <div class="col-lg-6">
                                            <textarea rows="3" name="desc" id="desc" class="form-control" placeholder="Enter a Remarks.."><?php echo htmlspecialchars(trim($description), ENT_QUOTES); ?></textarea>
                                        </div>
                                    </div>


                                    <div class="">
                                        <div class="row w-50 ">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toggleFields(document.getElementById('leave-cat').value);
        });

        function toggleFields(leaveCategory) {
            var fullday = document.getElementById("fullday");
            var halfday = document.getElementById("halfday");

            if (leaveCategory === "Full Day") {
                fullday.style.display = "block";
                halfday.style.display = "none";
            } else if (leaveCategory === "Half Day") {
                fullday.style.display = "none";
                halfday.style.display = "block";
            } else {
                fullday.style.display = "none";
                halfday.style.display = "none";
            }
        }
    </script>

    <?php include 'script.php'; ?>

</body>

</html>