<?php
// include database connection
include 'check_user_login.php';
$username = $_SESSION['login'];
$uid = $_SESSION['user_id']


?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Apply Leave</title>
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
                    include 'config/database.php';
                    $leave_type = $leave_cat = $start_date = $end_date = $leave_date = $time_period = $remark = "";

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

                        // If any leave record is found for the current date then error message.
              
                        $query = "SELECT *
                        FROM `leave` AS l
                        JOIN `employee` AS e ON l.user_id = e.user_id
                        WHERE e.user_id = :user_id
                        AND ('$start_date'  AND  '$end_date' BETWEEN l.start_date AND l.end_date OR '$leave_date' = l.leave_date)";
                        $stmt1 = $con->prepare($query);
                        $stmt1->bindParam(':user_id', $uid);
                        $stmt1->execute();
                        $alreadyapply = $stmt1->rowCount();
                        if ($alreadyapply > 0) {
                            $error_msg .= "<div >The date already been apply.</div>";
                        }

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
                            // include database connection

                            try {
                                // insert query
                                $query = "INSERT INTO `leave` (user_id, leave_type, leave_category, start_date, end_date, leave_date, time_period, description) VALUES (:user_id, :leave_type, :leave_category, :start_date, :end_date, :leave_date, :time_period, :desc)";

                                // Prepare the statement
                                $stmt = $con->prepare($query);

                                // Bind parameters
                                $stmt->bindParam(':user_id', $uid);
                                $stmt->bindParam(':leave_type', $leave_type);
                                $stmt->bindParam(':leave_category', $leave_cat);
                                $stmt->bindParam(':start_date', $start_date);
                                $stmt->bindParam(':end_date', $end_date);
                                $stmt->bindParam(':leave_date', $leave_date);
                                $stmt->bindParam(':time_period', $time_period);
                                $stmt->bindParam(':desc', $desc);

                                // Execute the query
                                if ($stmt->execute()) {
                                    //echo "<div class='alert alert-success'>Leave added successfully.</div>";
                                    header("Location: leave_read.php?action=created");

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
                            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center">Apply Leave</h3>
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                                <div class="card-body ">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="leave-type">Leave Type <span class="text-danger">*</span></label>
                                        <div class="col-lg-6 mb-3">
                                            <select class="form-control" id="leave-type" name="leave-type">
                                                <option selected="true" disabled>Select Leave Type</option>
                                                <option value="CL">CL</option>
                                                <option value="EL">EL</option>
                                                <option value="RH">RH</option>
                                                <option value="HPL">HPL</option>
                                                <option value="CCL">CCL</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label" for="leave-cat">Leave Category <span class="text-danger">*</span></label>
                                        <div class="col-lg-6">
                                            <select class="form-control" id="leave-cat" name="leave-cat" onchange="toggleFields(this.value);">
                                                <option selected="true" disabled>Select Leave Category</option>
                                                <option value="Full Day">Full Day</option>
                                                <option value="Half Day">Half Day</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="fullday" style="display: none;" class="mb-3 ">
                                        <div class="form-group row ">
                                            <label class="col-lg-4 col-form-label" for="start-date">Leave Start Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" name="start-date" placeholder="Select Leave Start Date">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="end-date">Leave End Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" name="end-date" placeholder="Select Leave End Date">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="halfday" style="display: none;" class="mb-3">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="leave-date">Leave Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" id="leave-date" name="leave-date" placeholder="Select Leave Date">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="time-period">Time Period <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3">
                                                <select class="form-control" id="time-period" name="time-period">
                                                    <option selected disabled>Select Time Period</option>
                                                    <option value="Morning">Morning</option>
                                                    <option value="Afternoon">Afternoon</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label" for="desc">Remarks <span class="text-danger">*</span></label>
                                        <div class="col-lg-6">
                                            <textarea rows="3" name="desc" id="desc" class="form-control" placeholder="Enter a Remarks.."></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-6 text-center">
                                            <button type="submit" class="btn btn-primary">Apply</button>
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