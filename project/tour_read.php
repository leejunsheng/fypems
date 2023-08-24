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
    <title>Work Tour List</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'topnav.php'; ?>
    <div class="page-header">
        <h1 class="ms-3">Work Tour List</h1>
    </div>

    <!-- PHP code to read records will be here -->
    <?php
    // include database connection
    include 'config/database.php';

    if (isset($_GET['update'])) {
        echo "<div class='alert alert-success'>Tour Record was updated.</div>";
    }

    // delete message prompt will be here
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    // if it was redirected from delete.php
    if ($action == 'approved') {
        echo "<div class='alert alert-success'>Tour apply was approved</div>";
    }

    if ($action == 'rejected') {
        echo "<div class='alert alert-success'>Tour apply was rejected</div>";
    }

    if ($action == 'created') {
        echo "<div class='alert alert-success'>Tour apply was create successfully.</div>";
    }

    if ($action == 'deleted') {
        echo "<div class='alert alert-success'>Record was deleted.</div>";
    }

    if ($action == 'faildelete') {
        echo "<div class='alert alert-danger'>The employee already has an applied, unable to delete.</div>";
    }

    // select all data
    if ($role == 1) {
        $query = "SELECT * FROM `tour` ORDER BY tour_id DESC";
    } else {
        $query = "SELECT * FROM `tour` WHERE user_id='$uid' ORDER BY tour_id DESC";
    }
    $stmt = $con->prepare($query);
    $stmt->execute();

    // this is how to get the number of rows returned
    $num = $stmt->rowCount();

    // link to create record form
    
    echo "
    <div>
        <a href='tour_apply.php' class='btn btn-primary m-b-1em my-3 ms-3'> Apply Tour <i class='fa-solid fa-plus mt-1'></i></a>
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
                                            <th>Tour ID</th>
                                            <th>User ID</th>
                                            <th>Tour Type</th>
                                            <th>Tour Category</th>
                                            <th>Tour Start Date</th>
                                            <th>Tour End Date</th>
                                            <th class='col-2'>Status</th>
                                            <th class='col-3'>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $startdate = date('d M Y', strtotime($row['start_date']));
            $enddate = date('d M Y', strtotime($row['end_date']));
            $tourdate = date('d M Y', strtotime($row['tour_date']));
            $timeperiod = $row['time_period'];

            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);

            // creating a new table row per record
            echo "<tr>";
            echo "<td class='text-center'>{$tour_id}</td>";
            echo "<td class='text-center'>{$user_id}</td>";
            echo "<td class='text-center'>{$tour_type}</td>";
            echo "<td>";
            if ($row['tour_category'] == 'Half Day') {
                echo $row['tour_category'] . " (" . $timeperiod . ")";
            } else {
                echo $row['tour_category'];
            }
            echo "</td>";

            echo "<td>";
            if ($row['tour_category'] == 'Half Day') {
                echo $tourdate;
            } else {
                echo $startdate;
            }
            echo "</td>";

            echo "<td>";
            if ($row['tour_category'] == 'Half Day') {
                echo $tourdate;
            } else {
                echo $enddate;
            }
            echo "</td>";

            if ($row['status'] == 0) {
                if ($role == 1) {
                    echo "<td>
                            <a href='tour_action.php?action=approve&id={$tour_id}' style='text-decoration: none;'>
                                <button class='btn btn-success btn-sm m-1 fw-bold p-1 rounded' style='border: none;'>APPROVE</button>
                            </a>
                            <a href='tour_action.php?action=reject&id={$tour_id}' style='text-decoration: none;'>
                                <button class='btn btn-danger btn-sm m-1 fw-bold p-1 rounded' style='border: none;'>REJECT</button>
                            </a>
                        </td>";
                } else {
                    echo "<td>
                            <span class='bg-warning text-dark fw-bold p-1 m-1 rounded'>PENDING</span>
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
            echo "<a href='tour_read_one.php?tour_id={$tour_id}' class='btn btn-info '>Read <i class='fa-brands fa-readme'></i></a>";
            echo "<a href='tour_update.php?tour_id={$tour_id}' class='btn btn-primary mx-lg-2 my-2 my-lg-0'>Edit <i class='fa-solid fa-pen-to-square'></i></a>";
            echo "<a href='#' onclick='delete_tour({$tour_id});' class='btn btn-danger'>Delete <i class='fa-solid fa-trash'></i></a>";
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
        function delete_tour(tour_id) {
            if (confirm('Are you sure?')) {
                // if the user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'tour_delete.php?tour_id=' + tour_id;
            }
        }
    </script>
</body>

</html>