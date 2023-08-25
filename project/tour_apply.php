<?php
// include database connection
include 'check_user_login.php';
$username = $_SESSION['login'];
$uid = $_SESSION['user_id']


?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Work Tour Apply</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'topnav.php'; ?>
    <?php
    // At the beginning of your PHP script
    error_reporting(E_ALL & ~E_NOTICE);

    // Your code here
    // ...

    // On line 34 or wherever you're using "tour-type"
    $tourType = isset($_POST['tour-type']) ? $_POST['tour-type'] : '';

    // Rest of your code
    // ...
    ?>
    <section class="h-100 pt-3">
        <div class="container min-vh-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-lg-9 col-xl-7">

                    <!-- container -->
                    <!-- PHP insert code will be here -->
                    <?php
                    include 'config/database.php';
                    $tour_type = $tour_cat = $start_date = $end_date = $tour_date = $time_period = $remark = "";

                    if ($_POST) {
                        $tour_type = $_POST['tour-type'];
                        $tour_cat = $_POST['tour-cat'];
                        $start_date = $_POST['start-date'];
                        $end_date = $_POST['end-date'];
                        $tour_date = $_POST['tour-date'];
                        $time_period = isset($_POST['time-period']) ? $_POST['time-period'] : null;
                        $desc = $_POST['desc'];


                        if (empty($start_date)) {
                            $start_date = null;
                        }

                        if (empty($end_date)) {
                            $end_date = null;
                        }

                        if (empty($tour_date)) {
                            $tour_date = null;
                        }

                        if (empty($time_period)) {
                            $time_period = null;
                        }


                        $error_msg = "";

                        // If any tour record is found for the current date, it displays an error message indicating that the date has already been applied for leave.
                        $today = date('Y-m-d');
                        $query = "SELECT *
                                  FROM `tour` AS l
                                  JOIN `employee` AS e ON l.user_id = e.user_id
                                  WHERE e.user_id = :user_id
                                  AND ('$start_date' BETWEEN l.start_date AND l.end_date OR '$tour_date' = l.tour_date)";

                        $stmt1 = $con->prepare($query);
                        $stmt1->bindParam(':user_id', $uid);
                        $stmt1->execute();
                        $alreadyapply = $stmt1->rowCount();
                        if ($alreadyapply > 0) {
                            $error_msg .= "<div>The date has already been applied.</div>";
                        }

                        // Additional checks and validations
                        if ($tour_type == "") {
                            $error_msg .= "<div>Please make sure tour type is not empty.</div>";
                        }

                        if ($tour_cat == "") {
                            $error_msg .= "<div>Please make sure tour category is not empty.</div>";
                        }

                        if ($desc == "") {
                            $error_msg .= "<div>Please make sure tour description is not empty.</div>";
                        }

                        if ($end_date < $start_date) {
                            $error_msg .= "<div>Please make sure end date is not earlier than start date.</div>";
                        }

                        if (!empty($error_msg)) {
                            echo "<div class='alert alert-danger'>{$error_msg}</div>";
                        } else {
                            // include database connection

                            try {
                                if ($role == 1) {
                                    $assigned_user_id = $_POST['employee']; // Retrieve selected employee ID from POST

                                    // Duplicate date check for admin-assigned users
                                    $query = "SELECT *
                                              FROM `tour` AS l
                                              JOIN `employee` AS e ON l.user_id = e.user_id
                                              WHERE e.user_id = :assigned_user_id
                                              AND ('$start_date' BETWEEN l.start_date AND l.end_date OR '$tour_date' = l.tour_date)";

                                    $stmt2 = $con->prepare($query);
                                    $stmt2->bindParam(':assigned_user_id', $assigned_user_id);
                                    $stmt2->execute();
                                    $alreadyapply1 = $stmt2->rowCount();
                                    if ($alreadyapply1 > 0) {
                                        $error_msg .= "<div>The date has already been applied for the assigned user.</div>";
                                        echo "<div class='alert alert-danger'>{$error_msg}</div>";
                                    } else {
                                        $query = "INSERT INTO `tour` (user_id, tour_type, tour_category, start_date, end_date, tour_date, time_period, description) VALUES (:user_id, :tour_type, :tour_category, :start_date, :end_date, :tour_date, :time_period, :desc)";

                                        // Prepare the statement
                                        $stmt = $con->prepare($query);

                                        // Bind parameters
                                        if ($role == 1) {
                                            $stmt->bindParam(':user_id', $assigned_user_id); // Use the selected employee ID
                                        } else {
                                            $stmt->bindParam(':user_id', $uid);
                                        }

                                        $stmt->bindParam(':tour_type', $tour_type);
                                        $stmt->bindParam(':tour_category', $tour_cat);
                                        $stmt->bindParam(':start_date', $start_date);
                                        $stmt->bindParam(':end_date', $end_date);
                                        $stmt->bindParam(':tour_date', $tour_date);
                                        $stmt->bindParam(':time_period', $time_period);
                                        $stmt->bindParam(':desc', $desc);

                                        // Execute the query
                                        if ($stmt->execute()) {

                                            //echo "<div class='alert alert-success'>Work Tour apply successfully.</div>";
                                            header("Location: tour_read.php?action=created");
                                        } else {
                                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                                        }
                                    }
                                }
                            } catch (PDOException $exception) {
                                die('ERROR: ' . $exception->getMessage());
                            }
                        }
                    } ?>

                    <!-- html form here where the product information will be entered -->

                    <div class="card" style="border-radius: 15px; ">
                        <div class="card-body p-4 p-md-5 ">
                            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center">Apply Tour</h3>
                            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                                <div class="card-body ">


                                    <?php if ($role == 1) : ?>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="employee">Assign Employee</label>
                                            <div class="col-lg-6 mb-3">
                                                <!-- Replace the options and values with actual employee data -->
                                                <select class="form-control" id="employee" name="employee">
                                                    <option selected disabled>Select Employee</option>

                                                    <?php
                                                    // include database connection
                                                    include 'config/database.php';
                                                    $query = "SELECT user_id, username,firstname,lastname FROM employee";
                                                    $stmt = $con->prepare($query);
                                                    $stmt->execute();

                                                    $num = $stmt->rowCount();
                                                    if ($num > 0) {
                                                        // table body will be here
                                                        // retrieve our table contents
                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            // extract row
                                                            extract($row);
                                                            $fullname = $firstname . ' ' . $lastname;
                                                            // creating new table row per record
                                                            echo "<option value=\"$user_id\">$fullname</option>";
                                                        }
                                                    }
                                                    ?>
                                                    <!-- ... More employee options ... -->
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="tour-type">Tour Type <span class="text-danger">*</span></label>
                                        <div class="col-lg-6 mb-3">
                                            <select class="form-control" id="tour-type" name="tour-type">
                                                <option selected disabled>Select Tour Type</option>
                                                <option value="CM">Client Meetings</option>
                                                <option value="TW">Training and Workshops</option>
                                                <option value="IT">Incentive Travel</option>
                                                <option value="CE">Corporate Events</option>
                                                <option value="SV">Supplier Visits</option>
                                                <option value="OTH">Others</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label" for="tour-cat">Tour Category <span class="text-danger">*</span></label>
                                        <div class="col-lg-6">
                                            <select class="form-control" id="tour-cat" name="tour-cat" onchange="toggleFields(this.value);">
                                                <option selected="true" disabled>Select Tour Category</option>
                                                <option value="Full Day">Full Day</option>
                                                <option value="Half Day">Half Day</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="fullday" style="display: none;" class="mb-3 ">
                                        <div class="form-group row ">
                                            <label class="col-lg-4 col-form-label" for="start-date">Tour Start Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" name="start-date" placeholder="Select tour Start Date">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="end-date">Tour End Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" name="end-date" placeholder="Select tour End Date">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="halfday" style="display: none;" class="mb-3">
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="tour-date">Tour Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" id="tour-date" name="tour-date" placeholder="Select tour Date">
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
                                        <label class="col-lg-4 col-form-label" for="desc">Description <span class="text-danger">*</span></label>
                                        <div class="col-lg-6">
                                            <textarea rows="3" name="desc" id="desc" class="form-control" placeholder="Enter a Remarks.."></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-6 text-center">
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
        function toggleFields(tourCategory) {
            var fullday = document.getElementById("fullday");
            var halfday = document.getElementById("halfday");

            if (tourCategory === "Full Day") {
                fullday.style.display = "block";
                halfday.style.display = "none";
            } else if (tourCategory === "Half Day") {
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