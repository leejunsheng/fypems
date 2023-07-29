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
    <title>Employee List</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'topnav.php'; ?>
    <div class="page-header ">
        <h1 class="ms-3">Read employee</h1>
    </div>

    <!-- PHP code to read records will be here -->
    <?php
    // include database connection
    include 'config/database.php';

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
        echo "<div class='alert alert-success'>The employee already has an applied, unable to delete.</div>";
    }


    // select all data
    if ($role == 1) {
        $query = "SELECT * FROM employee ORDER BY user_id DESC";
    } else {
        $query = "SELECT * FROM `employee` WHERE user_id='$uid'";
    }
    $stmt = $con->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
   
    
    // link to create record form
    echo "
            <div>
            <a href='employee_create.php' class='btn btn-primary m-b-1em my-3 ms-3'>  Create New employee <i class='fa-solid fa-plus mt-1'></i></a>
          </div>";

    //check if more than 0 record found
    if ($num > 0) {
        // data from database will be here
        echo "
                <div id='wrapper'>
                    <div id='content-wrapper'>
                        <div class='container-fluid'>
                            <div class='card mb-3'>
                                <div class='card-body'>
                                    <div class='table-responsive'>
                                        <table class='table table-bordered myTable' width='100%' > "; //start table

        //creating our table heading

        echo "   <thead> <tr data-sortable='true'>";

        echo "<th>User ID</th>";
        echo "<th>Image</th>";
        echo "<th>First Name</th>";
        echo "<th>Last Name</th>";
        echo "<th>Gender</th>";
        echo "<th>Birthday</th>";
        echo "<th>Department</th>";
        echo "<th>Registration Date</th>";
        echo "<th>Leave Balance</th>";
        echo "<th>Account Status</th>";
        echo "<th class='col-3' id='action-row'>Action</th>";
        echo "   </thead> </tr>";

        //GET DATA FROM DATABASE
        // table body will be here 
        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);
            
            // creating new table row per record
            echo "<tbody><tr>";
            echo "<td class=' text-center'>{$user_id}</td>";
            echo "<td style='width:100px;'><div'><img src='uploads/employee/$image' class='img-fluid'></div> </td>";
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
            echo "<td>{$leave_bal}</td>";

            if ($accstatus == 'active') {
                echo "<td class=' text-center text-success'>  <i class='fa-solid fa-circle-check fs-2'></i></td>";
            } else {
                echo "<td class=' text-center text-danger'> <i class='fa-solid fa-circle-xmark fs-2'></i> </td>";
            }
            echo "<td class=''>";
            echo "<div class='d-flex flex-column flex-lg-row '>";
            echo "<a href='employee_read_one.php?user_id={$user_id}' class='btn btn-info'>Read <i class='fa-brands fa-readme'></i></a>";
            echo "<a href='employee_update.php?user_id={$user_id}' class='btn btn-primary mx-lg-2 my-2 my-lg-0'>Edit <i class='fa-solid fa-pen-to-square'></i></a>";
            echo "<a href='#' onclick='delete_employee({$user_id});'  class='btn btn-danger'>Delete <i class='fa-solid fa-trash'></i></a>";
            echo "</td>";
            echo "</div>";

            echo "</tr>";
        }

        // end table

        echo "</tbody></table>";
    } else {
        echo "<div class='alert alert-danger'>No records found.</div>";
    }
    ?>

    </div>
    </div>

    <!-- end .container -->
    <?php include 'script.php'; ?>
    <script type='text/javascript'>
        // confirm record deletion
        function delete_employee(user_id) {
            if (confirm('Are you sure?')) {
                // if the user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'employee_delete.php?user_id=' + user_id;
            }
        }
    </script>
</body>

</html>