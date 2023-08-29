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
    <link href="style.css" rel="stylesheet">
    <title>Leave List</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'topnav.php'; ?>

    <div class="page-header">
        <h1 class="ms-3">Read Leave</h1>
    </div>

    <!-- PHP code to read records will be here -->


    <?php // include database connection 
    include 'config/database.php'; ?>

   
        <div class="container min-vh-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-lg-9 col-xl-7">
                    <div class="card" style="border-radius: 15px; ">
                        <div class="card-body p-4 p-md-5 text-center">

                            <!-- ... (previous code) ... -->

                            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 text-center">Attendance Records</h3>
                           
                                <form action="" method="get" class="mb-3">
                                    <label for="filterDate" class="form-label">Filter by Date:</label>
                                    <input type="date" name="filterDate" id="filterDate" class="form-control">
                                    <button type="submit" class="btn btn-primary mt-2">Apply Filter</button>
                                </form>
                 

                            <?php
                            // Handle date filter
                            $filterDate = isset($_GET['filterDate']) ? $_GET['filterDate'] : ''; ?>

                            <?php
                            $query = "SELECT *,
                                              CONCAT(e.firstname, ' ', e.lastname) AS FULLNAME
                                              FROM employee AS e 
                                              JOIN checkinout AS c ON e.user_id = c.user_id";

                            if ($role == 1 && !empty($filterDate)) {
                                $query .= " WHERE DATE(c.date) ='$filterDate'";
                            } else {
                                $query .= " ORDER BY c.attend_id DESC";
                            }
                            $stmt = $con->prepare($query);
                            $stmt->execute();
                            $num = $stmt->rowCount();
                            echo $num;



                            if ($num > 0) {
                                echo "
                            <table class='table table-hover table-responsive table-bordered' id='sortTable'> 
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Date</th>
                                        <th>Check In Time</th>
                                        <th>Check Out Time</th>
                                    </tr>
                                </thead>
                                <tbody>";

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                    echo "<tr>";
                                    echo "<td class='text-center'>{$user_id}</td>";
                                    echo "<td>{$date}</td>";
                                    echo "<td>{$checkin_time}</td>";
                                    echo "<td>{$checkout_time}</td>";
                                    echo "</tr>";
                                }

                                echo "
                                </tbody>
                            </table>";
                            } else {
                                echo "<p>No attendance records found.</p>";
                            }

                            ?>
                                <a href='#' onclick='printTable()' class='btn btn-primary m-b-1em my-3 mx-2'>Print Table <i class='fa-solid fa-print'></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <!-- end .container -->
    <?php include 'script.php'; ?>

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_leave(leave_id) {
            if (confirm('Are you sure?')) {
                // if the user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'leave_delete.php?leave_id=' + leave_id;
            }
        }
    </script>


</body>

</html>