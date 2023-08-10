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
    <?php
    // include database connection
    include 'config/database.php';

    if (isset($_GET['update'])) {
        echo "<div class='alert alert-success'>Leave Record was updated.</div>";
    }

    // delete message prompt will be here
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    // if it was redirected from delete.php
    if ($action == 'approved') {
        echo "<div class='alert alert-success'>Leave apply was approved</div>";
    }

    if ($action == 'rejected') {
        echo "<div class='alert alert-success'>Leave apply was rejected</div>";
    }

    if ($action == 'created') {
        echo "<div class='alert alert-success'>Leave apply was create successfully.</div>";
    }

    if ($action == 'deleted') {
        echo "<div class='alert alert-success'>Record was deleted.</div>";
    }

    if ($action == 'faildelete') {
        echo "<div class='alert alert-success'>The employee already has an applied, unable to delete.</div>";
    }

    if ($action == 'statusfail') {
        echo "<div class='alert alert-danger'>Unable to update leave status. Please try again.</div>";
    }

    if ($action == 'updatefail') {
        echo "<div class='alert alert-danger'>Unable to update leave balance. Please try again.</div>";
    }

    if ($action == 'leavebal') {
        echo "<div class='alert alert-danger'>Leave balance is insufficient to approve the leave.</div>";
    }

    if ($action == 'fetchfail') {
        echo "<div class='alert alert-danger'>Unable to fetch user information. Please try again.</div>";
    }


    // select all data
    if ($role == 1) {
        $query = "SELECT * FROM `leave` ORDER BY leave_id DESC";
    } else {
        $query = "SELECT * FROM `leave` WHERE user_id='$uid' ORDER BY leave_id DESC";
    }
    $stmt = $con->prepare($query);
    $stmt->execute();

    // this is how to get the number of rows returned
    $num = $stmt->rowCount();

    // link to create record form
    echo "
    <div>
        <a href='leave_apply.php' class='btn btn-primary m-b-1em my-3 ms-3'> Apply Leave <i class='fa-solid fa-plus mt-1'></i></a>
    </div>";

    // check if more than 0 records found
    if ($num > 0) {
        // data from the database will be here
        echo "
        <div id='wrapper'>
            <div id='content-wrapper'>
                <div class='container-fluid'>
                    <div class='card mb-3'>
                        <div class='card-body'>
                            <div class='table-responsive'>
                            <table class='table table-hover table-responsive table-bordered' id='sortTable'> 
                                    <thead>
                                        <tr>
                                            <th>Leave ID</th>
                                            <th>Leave Type</th>
                                            <th>Leave Category</th>
                                            <th>Leave Start Date</th>
                                            <th>Leave End Date</th>
                                            <th class='col-2'>Status</th>
                                           <th class='col-3' id='action-row'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $startdate = date('d M Y', strtotime($row['start_date']));
            $enddate = date('d M Y', strtotime($row['end_date']));
            $leavedate = date('d M Y', strtotime($row['leave_date']));
            $timeperiod = $row['time_period'];

            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);

            // creating a new table row per record
            echo "<tr>";
            echo "<td class='text-center'>{$leave_id}</td>";
            echo "<td class='text-center'>{$leave_type}</td>";
            echo "<td>";
            if ($row['leave_category'] == 'Half Day') {
                echo $row['leave_category'] . " (" . $timeperiod . ")";
            } else {
                echo $row['leave_category'];
            }
            echo "</td>";

            echo "<td>";
            if ($row['leave_category'] == 'Half Day') {
                echo $leavedate;
            } else {
                echo $startdate;
            }
            echo "</td>";

            echo "<td>";
            if ($row['leave_category'] == 'Half Day') {
                echo $leavedate;
            } else {
                echo $enddate;
            }
            echo "</td>";

            if ($row['status'] == 0) {
                if ($role == 1) {
                    echo "<td>
                            <a href='leave_action.php?action=approve&id={$leave_id}' style='text-decoration: none;'>
                                <button class='btn btn-success btn-sm m-1 fw-bold p-1 rounded' style='border: none;'>Approve</button>
                            </a>
                            <a href='leave_action.php?action=reject&id={$leave_id}' style='text-decoration: none;'>
                                <button class='btn btn-danger btn-sm m-1 fw-bold p-1 rounded' style='border: none;'>Reject</button>
                            </a>
                        </td>";
                } else {
                    echo "<td>
                            <span class='bg-warning text-dark fw-bold p-1 rounded'>PENDING</span>
                        </td>";
                }
            } elseif ($row['status'] == 1) {
                echo "<td>
                        <span class='bg-success text-light fw-bold p-1 rounded'>APPROVED <i class='fa-solid fa-check bg-success'></i></span>
                    </td>";
            } else {
                echo "<td>
                        <span class='bg-danger text-light fw-bold p-1 rounded'>REJECTED <i class='fa-solid fa-xmark bg-danger'></i></span>
                    </td>";
            }

            echo "<td class=''>";

            echo "<div class='d-flex flex-column flex-lg-row '>";
            echo "<a href='leave_read_one.php?leave_id={$leave_id}' class='btn btn-info '>Read <i class='fa-brands fa-readme'></i></a>";
            echo "<a href='leave_update.php?leave_id={$leave_id}' class='btn btn-primary mx-lg-2 my-2 my-lg-0'>Edit  <i class='fa-solid fa-pen-to-square'></i></a>";
            echo "<a href='#' onclick='delete_leave({$leave_id});' class='btn btn-danger'>Delete <i class='fa-solid fa-trash'></i></a>";
            echo "</div>";

            echo "</td>";

            echo "</tr>";
        }

        // end table
        echo "</tbody>
        
              </table>
          
            </div>
          </div>
        </div>
        
      </div>
    </div>
    
  </div>";
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