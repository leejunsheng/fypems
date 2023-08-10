<?php
// include database connection
include 'check_user_login.php';
?>



<!DOCTYPE HTML>
<html>

<head>
    <title>Read Leave Detail</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'topnav.php'; ?>
    <!-- container -->
    <div class="container-fluid px-0">
        <!-- container -->
        <div class="container my-3">

            <div class="page-header my-3">
                <h1>Read employee</h1>
            </div>

            <!-- PHP read one record will be here -->
            <?php

            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $leave_id = isset($_GET['leave_id']) ? $_GET['leave_id'] : die('ERROR: Record ID not found.');

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT *
                FROM `leave` AS l
                JOIN employee AS e ON l.user_id = e.user_id
                WHERE l.leave_id = :leave_id";

                $stmt = $con->prepare($query);

                // Bind the parameter
                $stmt->bindParam(":leave_id", $leave_id);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form

                $leave_id = $row['leave_id'];
                $leave_type = $row['leave_type'];
                $leave_category = $row['leave_category'];
                $start_date = $row['start_date'];
                $end_date = $row['end_date'];
                $leave_date = $row['leave_date'];
                $time_period = $row['time_period'];
                $status = $row['status'];
                $entry_date = $row['entry_date'];
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                // shorter way to do that is extract($row)
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <!-- HTML read one record table will be here -->
            <!--we have our html table here where the record will be displayed-->
            <table class='table table-hover table-responsive table-bordered' id='sortTable'> 
                <tr>
                    <td>Leave ID</td>
                    <td><?php echo htmlspecialchars($leave_id, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><?php echo htmlspecialchars($firstname . ' ' . $lastname, ENT_QUOTES); ?></td>
                </tr>

                <tr>
                    <td>Leave Type</td>
                    <td><?php echo htmlspecialchars($leave_type, ENT_QUOTES);  ?></td>
                </tr>

                <tr>
                    <td>Leave Category</td>
                    <td><?php echo htmlspecialchars($leave_category, ENT_QUOTES);  ?></td>
                </tr>

                <?php
                if ($leave_category == "Full Day") {
                    echo "
                 <tr>
                 <td>Start Date</td>
                 <td>" . htmlspecialchars($start_date, ENT_QUOTES) . "</td>
                 </tr>
                 <tr>
                 <td>End Date</td>
                 <td>" . htmlspecialchars($end_date, ENT_QUOTES) . "</td>
                 </tr>";
                } else {
                    echo "
                <tr>
                <td>Leave Date</td>
                <td>" . htmlspecialchars($leave_date, ENT_QUOTES) . "</td>
                </tr>
                <tr>
                <td>Time Period</td>
                <td>" . htmlspecialchars($time_period, ENT_QUOTES) . "</td>
                </tr>";
                }
                ?>

                <tr>
                    <td>Status</td>
                    <td><?php
                        if ($status == 0) {
                            echo "Pending";
                        } elseif ($status == 1) {
                            echo "Approved";
                        } else {
                            echo "Rejected";
                        }
                        ?></td>
                </tr>
                <tr>
                    <td>Apply Date</td>
                    <td><?php echo htmlspecialchars($entry_date, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php echo "<a href='leave_update.php?leave_id={$leave_id}' class='btn btn-primary'>Edit <i class='fa-solid fa-pen-to-square'></i></a>"; ?>
                        <a href='#' onclick='printTable()' class='btn btn-primary m-b-1em my-3 mx-2'>Print Table <i class="fa-solid fa-print"></i></a>
                        <a href=leave_read.php class='btn btn-secondary m-r-1em '><i class="fa-solid fa-circle-arrow-left"></i> Back to read leave </a>
                    </td>
                </tr>
            </table>
         
        </div>
    </div>
    <!-- end .container -->

    <?php include 'script.php'; ?>

</body>

</html>