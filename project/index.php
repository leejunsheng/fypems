<?php
// To check user are login
include 'check_user_login.php';

$role = $_SESSION['role'];

?>



<!DOCTYPE HTML>
<html>

<head>
  <title>Dashboard</title>
  <!-- Latest compiled and minified Bootstrap CSS -->
  <?php include 'head.php'; ?>

</head>


<body>

  <!-- container -->
  <?php include 'topnav.php'; ?>
 

  <section class="min-vh-100 pt-3">
    
    <div class="container row m-0 d-flex justify-content-between align-items-center">
      
      <?php
      include 'config/database.php';

      if (isset($_GET['update'])) {
        echo "<div class='alert alert-success mt-3'>Password change successful</div>";
      }

      // Get current date
      $today = date('Y-m-d');

      // Query to fetch employees on leave today
      $query = "SELECT COUNT(DISTINCT e.user_id) AS total_users
          FROM `leave` AS l
          JOIN `employee` AS e ON l.user_id = e.user_id
          WHERE ('$today' BETWEEN l.start_date AND l.end_date
                  OR '$today' = l.leave_date)
            AND l.status = 1";

      $stmt = $con->prepare($query);
      $stmt->execute();
      $leaveCount = $stmt->fetchColumn();

      // Query to fetch employees on work tour today
      $query = "SELECT COUNT(DISTINCT e.user_id) AS total_user
          FROM `tour` AS l
          JOIN `employee` AS e ON l.user_id = e.user_id
          WHERE ('$today' BETWEEN l.start_date AND l.end_date
                  OR '$today' = l.tour_date)
            AND l.status = 1";

      $stmt5 = $con->prepare($query);
      $stmt5->execute();
      $tourCount = $stmt->fetchColumn();

      // Query to fetch total employees
      $query = "SELECT * FROM employee";
      $stmt2 = $con->prepare($query);
      $stmt2->execute();
      $employee = $stmt2->fetchColumn();

      // Query to fetch pending leave requests
      $query = "SELECT * FROM `leave` WHERE `leave`.`status` = 0;";
      $stmt3 = $con->prepare($query);
      $stmt3->execute();
      $pending_leave = $stmt3->rowCount();

      // Query to fetch pending work tour requests
      $query = "SELECT * FROM `tour` WHERE `tour`.`status` = 0;";
      $stmt4 = $con->prepare($query);
      $stmt4->execute();
      $pending_tour = $stmt4->rowCount();

      // Employees on Leave Today
      if ($leaveCount > 0 || $tourCount > 0) {
        echo "
        <div class='card my-2' style='border-radius: 15px;'>
            <div class='card-body'>
                <h3 class='mb-4'>Today's Off-Site Employees</h3>
                <p>Total Employees on Work Tour Today: <a class='mt-3' href='notice_read.php'>$tourCount</a></p>
                <p>Total Employees on Leave Today: <a class='mt-3' href='notice_read.php'>$leaveCount</a></p>
            </div>
        </div>";
      } else {
        echo "<div class='alert alert-info'>No employees on leave or work tour today.</div>";
      }

      // Summary Table
      echo "
        <div class='row text-center justify-content-center mt-3'>
          <div class='col-md-4 '>
              <div class='card text-white bg-primary boxshadow'>
                  <div class='card-body'>
                      <h5 class='card-title'>Total Employees</h5>
                      <p class='card-text fs-5'>$employee</p>
                      <a class='btn btn-light mt-3' href='employee_read.php'>View List</a>
                  </div>
              </div>
          </div>

        <div class='col-md-4 py-md-0 pt-3'>
                <div class='card text-white bg-primary boxshadow'>
                    <div class='card-body'>
                        <h5 class='card-title'>Pending Tour Requests</h5>
                        <p class='card-text fs-5'>$pending_tour</p>
                        <a class='btn btn-light mt-3' href='tour_read.php'>View List</a>
                    </div>
                </div>
        </div>

          <div class='col-md-4'>
              <div class='card text-white bg-primary boxshadow'>
                  <div class='card-body'>
                      <h5 class='card-title'>Pending Leave Requests</h5>
                      <p class='card-text fs-5'>$pending_leave</p>
                      <a class='btn btn-light mt-3' href='leave_read.php'>View List</a>
                  </div>
              </div>
          </div>

       
      </div>";
      ?>
    </div>
    
   </div>

  </section>
      <?php include 'script.php'; ?>
 
  
</body>

</html>