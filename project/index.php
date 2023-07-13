<?php
// To check user are login
include 'check_user_login.php';

$role = $_SESSION['role'];
echo "Role: " . $role;
?>



<!DOCTYPE HTML>
<html>

<head>
  <title>Dashboard</title>
  <!-- Latest compiled and minified Bootstrap CSS -->
  <?php include 'head.php'; ?>

</head>


<body>
  <div>
    <!-- container -->
    <?php include 'topnav.php'; ?>


    <div class="container-fluid row m-0  d-flex justify-content-between align-items-center">
      <?php
      include 'config/database.php';

      if (isset($_GET['update'])) {
        echo "<div class='alert alert-success mt-3'>Password change successful</div>";
      }

      // Get current date
      $today = date('Y-m-d');

      // Query to fetch employees on leave today
      $query = "SELECT * FROM `leave` AS l JOIN employee AS e ON l.user_id = e.user_id WHERE '2023-06-24' BETWEEN l.start_date AND l.end_date";
      $stmt = $con->prepare($query);
      $stmt->execute();
      $leaveCount = $stmt->rowCount();

      // Query to fetch total employees
      $query = "SELECT * FROM employee";
      $stmt2 = $con->prepare($query);
      $stmt2->execute();
      $employee = $stmt2->rowCount();

      // Query to fetch pending leave requests
      $query = "SELECT * FROM `leave` WHERE `leave`.`status` = 0;";
      $stmt3 = $con->prepare($query);
      $stmt3->execute();
      $pending_leave = $stmt3->rowCount();

      // Employees on Leave Today
      if ($leaveCount > 0) {
        echo "
    <div class='card my-2' style='border-radius: 15px;'>
        <div class='card-body'>
            <h3 class='mb-4'>Employees on Leave Today</h3>
            <ul>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $firstname = $row['firstname'];
          $lastname = $row['lastname'];
          echo "<ol><li>{$firstname} {$lastname}</li></ol>";
        }
        echo "
            </ul>
        </div>
    </div>";
      } else {
        echo "<div class='alert alert-info'>No employees on leave today.</div>";
      }

      // Summary Table
      echo "
        <div class='row text-center justify-content-center mt-3'>
          <div class='col-md-4'>
              <div class='card text-white bg-primary boxshadow'>
                  <div class='card-body'>
                      <h5 class='card-title'>Total Employees</h5>
                      <p class='card-text fs-5'>$employee</p>
                      <a class='btn btn-light mt-3' href='employee_read.php'>View List</a>
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

      <?php include 'script.php'; ?>

</body>

</html>