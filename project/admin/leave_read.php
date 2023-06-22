<?php
// include database connection
include 'check_user_login.php';
$username = $_SESSION['login'];
$uid = $_SESSION['user_id']
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Leave List</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'topnav.php'; ?>
    <!-- container -->
    <div class="container-fluid ">
        <!-- container -->
        <div class="container my-3">

            <div class="page-header ">
                <h1>Read Leave</h1>
            </div>

            <!-- PHP code to read records will be here -->
            <?php
            // include database connection
            include '../config/database.php';

            if (isset($_GET['update'])) {
                echo "<div class='alert alert-success'>Record was updated.</div>";
            }

            // delete message prompt will be here
            $action = isset($_GET['action']) ? $_GET['action'] : "";

            // if it was redirected from delete.php
            if ($action == 'created') {
                echo "<div class='alert alert-success'>employee was create successfully.</div>";
            }

            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }

            if ($action == 'faildelete') {
                echo "<div class='alert alert-success'>The employee already have an order unable to delete.</div>";
            }

            // select all data
            $query = "SELECT * FROM `leave` WHERE user_id='$uid' ORDER BY leave_id DESC";
            $stmt = $con->prepare($query);
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            // link to create record form

            echo "
            <div>
            <a href='apply_leave.php' class='btn btn-primary m-b-1em my-3'> Apply leave <i class='fa-solid fa-plus mt-1'></i></a>
          </div>";

            //check if more than 0 record found
            if ($num > 0) {

                // data from database will be here
                echo "<table class='table table-hover table-responsive table-bordered read-table'>"; //start table

                //creating our table heading

                echo "<tr>";
                echo "<th>S.No.</th>";
                echo "<th>Leave Type</th>";
                echo "<th>Leave Category</th>";
                echo "<th>Leave Start Date</th>";
                echo "<th>Leave End Date</th>";
                echo "<th>Status</th>";
                echo "<th>Action</th>";
                echo "</tr>";


                //GET DATA FROM DATABASE
                // table body will be here 
                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $startdate = date('d M Y', strtotime($row['start_date']));
                    $enddate = date('d M Y', strtotime($row['end_date']));
                    $leavedate = date('d M Y', strtotime($row['leave_date']));
                    $timeperiod = $row['time_period'];

                    // extract row
                    // this will make $row['firstname'] to just $firstname only
                    extract($row);

                    // Creating a new table row per record
                    echo "<tr>";
                    echo "<td class='text-center'>{$leave_id}</td>";
                    echo "<td class='text-center'>{$leave_type}</td>";
                    echo "<td>";

                    if ($leave_category == 'Half Day') {
                        echo $leave_category . " (" . $timeperiod . ")";
                    } else {
                        echo $leave_category;
                    }

                    echo "</td>";
                    echo "<td>";

                    if ($leave_category == 'Half Day') {
                        echo $leavedate;
                    } else {
                        echo $startdate;
                    }

                    echo "</td>";
                    echo "<td>";

                    if ($leave_category == 'Half Day') {
                        echo $leavedate;
                    } else {
                        echo $enddate;
                    }

                    echo "</td>";

                    if ($status == 0) {
                        echo '<td>
                            <a href="approve-leave.php?id=' . $id . '">
                                <button class="btn btn-success btn-sm">Approve</button>
                            </a>
                            <a href="reject-leave.php?id=' . $id . '">
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </a>
                        </td>';
                    } elseif ($status == 1) {
                        echo '<td style="color: green; font-weight: 700;">Approved</td>';
                    } else {
                        echo '<td style="color: red; font-weight: 700;">Rejected</td>';
                    }

                    echo "</tr>";


                    echo "<td class=''>";
                    echo "<a href='leave_read_one.php?leave_id={$leave_id}' class='btn btn-info m-r-1em mx-2'>Read <i class='fa-brands fa-readme'></i></a>";
                    echo "<a href='employee_update.php?user_id={$user_id}' class='btn btn-primary   mx-2 my-2'>Edit <i class='fa-solid fa-pen-to-square'></i></a>";
                    echo "<a href='#' onclick='delete_employee({$user_id});'  class='btn btn-danger  mx-2'>Delete <i class='fa-solid fa-trash'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }

                // end table
                echo "</table>";
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }
            ?>
        </div>
    </div>

    <!-- end .container -->
    <?php include 'script.php'; ?>

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_employee(user_id) {
            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'employee_delete.php?user_id=' + user_id;
            }
        }
    </script>
</body>

</html>