<?php
// include database connection
include 'check_user_login.php';
$username = $_SESSION['login'];
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>




<!DOCTYPE HTML>
<html>

<head>
    <title>Attendance</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>


<body>

    <?php include 'topnav.php'; ?>

    <section class="h-100 pt-3">
        <div class="container min-vh-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-lg-9 col-xl-7">
                    <div class="card" style="border-radius: 15px; ">
                        <div class="card-body p-4 p-md-5 text-center">


                            <?php
                            // Set the time zone to Malaysia (GMT+8)
                            $timezone = new DateTimeZone('Asia/Kuala_Lumpur');
                            $now = new DateTime('now', $timezone);

                            // Get the timestamp and date in Malaysia time
                            $timestamp = $now->format('Y-m-d H:i:s');
                            $date = $now->format('Y-m-d');

                            include 'config/database.php';

                            // select all data
                            if ($role == 1) {
                                $query = "SELECT *,
                                CONCAT(e.firstname, ' ', e.lastname) AS FULLNAME
                                FROM employee AS e 
                                JOIN checkinout AS c ON e.user_id = c.user_id
                                WHERE date='$date' ORDER BY c.attend_id DESC " ;
                            } else {
                                $query = "SELECT * FROM checkinout WHERE user_id='$uid' ORDER BY attend_id DESC";
                            }
                            $stmt = $con->prepare($query);
                            $stmt->execute();

                            // this is how to get the number of rows returned
                            $num = $stmt->rowCount();


                            $action = isset($_GET['action']) ? $_GET['action'] : "";

                            if ($action == 'checkin') {
                                echo "<div class='alert alert-success'>Check In Sucessful.</div>";
                            }

                            if ($action == 'checkout') {
                                echo "<div class='alert alert-success'>Check Out Sucessful.</div>";
                            }

                            if ($action == 'fail') {
                                echo "<div class='alert alert-success'>You already checked in today.</div>";
                            }

                            if ($action == 'failout') {
                                echo "<div class='alert alert-success'>You already checked out today.</div>";
                            }
                            ?>

                            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center ">Record Check In & Check Out</h3>

                            <label id="clockLabel" class="fw-bold">Loading...</label><br>
                            <!-- ... (other form inputs) ... -->

                            <div class="d-flex justify-content-center pt-3">
                                <br>
                                <!-- Check In and Check Out buttons -->
                                <a href='attendance_action.php?action=checkin' style='text-decoration: none;'>
                                    <button class='btn btn-success btn-sm m-1 fw-bold p-1 rounded' style='border: none;'>CHECK IN</button>
                                </a>
                                <a href='attendance_action.php?action=checkout' style='text-decoration: none;'>
                                    <button class='btn btn-danger btn-sm m-1 fw-bold p-1 rounded' style='border: none;'>CHECK OUT</button>
                                </a>
                                <br>
                            </div>


                            <?php
                            if ($num > 0) {
                                echo "
                                <table class='table table-hover table-responsive table-bordered' id='sortTable'> 
                                        <thead>
                                       <tr>
                                       <th>User ID</th>
                                        <th>Check In Time</th>
                                        <th>Check Out Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>";
                            }

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                // extract row

                                extract($row);

                                echo "<tr>";
                                echo "<td class=' text-center'>{$user_id}</td>";
                                echo "<td>{$checkin_time}</td>";
                                echo "<td>{$checkout_time}</td>";
                            }
                            ?>



                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <script>
        function updateClock() {
            var label = document.getElementById("clockLabel");
            setInterval(function() {
                var now = new Date();
                var hours = String(now.getHours()).padStart(2, "0");
                var minutes = String(now.getMinutes()).padStart(2, "0");
                var seconds = String(now.getSeconds()).padStart(2, "0");
                var currentTime = hours + ":" + minutes + ":" + seconds;
                label.textContent = "Current Time: " + currentTime;
            }, 1000); // Update every 1 second
        }
        updateClock(); // Call the function to start updating the clock
    </script>
    <?php include 'script.php'; ?>

</body>

</html>