<?php
// include database connection
include 'check_user_login.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>employee List</title>
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
                <h1>Read employee</h1>
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
            $query = "SELECT * FROM employee ORDER BY user_id DESC";


            $stmt = $con->prepare($query);
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();
            
            // link to create record form

            echo "
            <div>
            <a href='employee_create.php' class='btn btn-primary m-b-1em my-3'>  Create New employee <i class='fa-solid fa-plus mt-1'></i></a>
          </div>";

            //check if more than 0 record found
            if ($num > 0) {

                // data from database will be here
                echo "<table class='table table-hover table-responsive table-bordered read-table'>"; //start table

                //creating our table heading
                echo "<tr>";
                echo "<th class='col-1'>User ID</th>";
                echo "<th class='col-1'>Image</th>";
                echo "<th class='col-1'>First Name</th>";
                echo "<th class='col-1'>Last Name</th>";
                echo "<th class='col-1'>Gender</th>";
                echo "<th class='col-2'>Birthday</th>";
                echo "<th class='col-2'>Department</th>";
                echo "<th class='col-2'>Registration Date</th>";
                echo "<th class='col-1'>Account Status</th>";
                echo "<th class='col-2'>Action</th>";
                echo "</tr>";

                //GET DATA FROM DATABASE
                // table body will be here 
                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // extract row
                    // this will make $row['firstname'] to just $firstname only
                    extract($row);
                    // creating new table row per record
                    echo "<tr>";
                    echo "<td class=' text-center'>{$user_id}</td>";
                    echo "<td style='width:100px;'><div'><img src='../uploads/employee/$image' class='img-fluid'></div> </td>";
                    echo "<td>{$firstname}</td>";
                    echo "<td>{$lastname}</td>";


                    if ($gender == 'Male') {
                        echo "<td class=' text-center text-primary'>  <i class='fa-solid fa-person fs-2'></i> </td>";
                    } else {
                        echo "<td class=' text-center text-danger'> <i class='fa-solid fa-person-dress fs-2'></i> </td>";
                    }

                    echo "</td>";
                    echo "<td>{$datebirth}</td>";
                    echo "<td>{$department}</td>";
                    echo "<td>{$registration_dt}</td>";


                    if ($accstatus == 'active') {
                        echo "<td class=' text-center text-success'>  <i class='fa-solid fa-circle-check fs-2'></i></td>";
                    } else {
                        echo "<td class=' text-center text-danger'> <i class='fa-solid fa-circle-xmark fs-2'></i> </td>";
                    }
                    echo "<td class=''>";
                    echo "<a href='employee_read_one.php?user_id={$user_id}' class='btn btn-info m-r-1em mx-2'>Read <i class='fa-brands fa-readme'></i></a>";
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