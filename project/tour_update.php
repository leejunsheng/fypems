<?php
include 'check_user_login.php';

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Update Work Tour</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'topnav.php'; ?>
    <section class="h-100 pt-3">
        <div class="container min-vh-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-lg-9 col-xl-7">

                    <!-- container -->
                    <!-- PHP insert code will be here -->
                    <?php
                    $tour_id = isset($_GET['tour_id']) ? $_GET['tour_id'] : die('ERROR: Record ID not found.');

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
                        $query = "SELECT * FROM `tour` WHERE tour_id = ? LIMIT 0,1";
                        $stmt = $con->prepare($query);

                        // this is the first question mark
                        $stmt->bindParam(1, $tour_id);

                        // execute our query
                        $stmt->execute();

                        // store retrieved row to a variable
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        // values to fill up our form
                        $user_id = $row['user_id'];
                        $tour_type = $row['tour_type'];
                        $tour_category = $row['tour_category'];
                        $start_date = $row['start_date'];
                        $end_date = $row['end_date'];
                        $tour_date = $row['tour_date'];
                        $time_period = $row['time_period'];
                        $description = $row['description'];
                    }

                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }

                    if ($_POST) {
                        $tour_type = $_POST['tour-type'];
                        $tour_cat = $_POST['tour-cat'];
                        $start_date = $_POST['start-date'];
                        $end_date = $_POST['end-date'];
                        $tour_date = isset($_POST['tour-date']) ? $_POST['tour-date'] : null;
                        $time_period = isset($_POST['time-period']) ? $_POST['time-period'] : null;
                        $desc = $_POST['desc'];

                        if ($tour_cat == "Full Day") {
                            // Set tour_date and time_period to null
                            $tour_date = null;
                            $time_period = null;
                        } elseif ($tour_cat == "Half Day") {
                            // Set start_date and end_date to null
                            $start_date = null;
                            $end_date = null;
                        }

                        $error_msg = "";
                        if ($tour_type == "") {
                            $error_msg .= "<div >Please make sure tour type is not empty.</div>";
                        }

                        if ($tour_cat == "") {
                            $error_msg .= "<div >Please make sure tour category is not empty.</div>";
                        }

                        if ($desc == "") {
                            $error_msg .= "<div >Please make sure tour description is not empty.</div>";
                        }

                        if (!empty($error_msg)) {
                            echo "<div class='alert alert-danger'>{$error_msg}</div>";
                        } else {
                            include 'config/database.php';
                            try {
                                // insert query
                                $query = "UPDATE `tour` SET tour_type = :tour_type, tour_category = :tour_category, start_date = :start_date, end_date = :end_date, tour_date = :tour_date, time_period = :time_period, description = :desc WHERE tour_id = :tour_id";

                                // Prepare the statement
                                $stmt = $con->prepare($query);

                                // Bind parameters
                                $stmt->bindParam(':tour_id', $tour_id);
                                $stmt->bindParam(':tour_type', $tour_type);
                                $stmt->bindParam(':tour_category', $tour_cat);
                                $stmt->bindParam(':start_date', $start_date);
                                $stmt->bindParam(':end_date', $end_date);
                                $stmt->bindParam(':tour_date', $tour_date);
                                $stmt->bindParam(':time_period', $time_period);
                                $stmt->bindParam(':desc', $desc);

                                // Execute the query
                                if ($stmt->execute()) {
                                    echo "<div class='alert alert-success'>Record was updated.</div>";
                                    header("Location: tour_read.php?update={$user_id}");
                                } else {
                                    echo "<div class='alert alert-danger'>Unable to update record.</div>";
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
                            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center">Apply Tour</h3>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?tour_id={$tour_id}"); ?>" method="post" enctype="multipart/form-data">
                                <div class="card-body ">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label" for="tour-type">tour Type <span class="text-danger">*</span></label>
                                        <div class="col-lg-6 mb-3">
                                            <select class="form-control" id="tour-type" name="tour-type">
                                                <option value="CL" <?php if ($tour_type == 'CL') echo 'selected'; ?>>CL</option>
                                                <option value="EL" <?php if ($tour_type == 'EL') echo 'selected'; ?>>EL</option>
                                                <option value="RH" <?php if ($tour_type == 'RH') echo 'selected'; ?>>RH</option>
                                                <option value="HPL" <?php if ($tour_type == 'HPL') echo 'selected'; ?>>HPL</option>
                                                <option value="CCL" <?php if ($tour_type == 'CCL') echo 'selected'; ?>>CCL</option>
                                                <option value="Others" <?php if ($tour_type == 'Others') echo 'selected'; ?>>Others</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-lg-4 col-form-label" for="tour-cat">tour Category <span class="text-danger">*</span></label>
                                        <div class="col-lg-6">
                                            <select class="form-control" id="tour-cat" name="tour-cat" onchange="toggleFields(this.value);">
                                                <option value="Full Day" <?php if ($tour_category === 'Full Day') echo 'selected'; ?>>Full Day</option>
                                                <option value="Half Day" <?php if ($tour_category === 'Half Day') echo 'selected'; ?>>Half Day</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="fullday" style="display: none;" class="mb-3 ">
                                        <div class="form-group row ">
                                            <label class="col-lg-4 col-form-label" for="start-date">tour Start Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" name="start-date" value="<?php echo htmlspecialchars($start_date, ENT_QUOTES);  ?>" placeholder="Select tour Start Date">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="end-date">tour End Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" name="end-date" value="<?php echo htmlspecialchars($end_date, ENT_QUOTES);  ?>" placeholder="Select tour End Date">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="halfday" class="mb-3" style='display: none;'>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="tour-date">tour Date <span class="text-danger">*</span></label>
                                            <div class="col-lg-6 mb-3 form-outline datepicker">
                                                <input type="date" class="form-control" id="tour-date" name="tour-date" value="<?php echo htmlspecialchars($tour_date, ENT_QUOTES); ?>" placeholder="Select tour Date">
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
                                        <div class="text-end ">
                                        <input type='submit' value='Update' class='btn btn-primary' />
                                        <a href=tour_read.php class='btn btn-secondary m-r-1em mx-2'><i class="fa-solid fa-circle-arrow-left"></i> Back to read tour</a>
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
            toggleFields(document.getElementById('tour-cat').value);
        });

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