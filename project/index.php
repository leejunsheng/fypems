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

      $query = "SELECT * FROM employee";
      $stmt = $con->prepare($query);
      $stmt->execute();
      $employee = $stmt->rowCount();


      $query = "SELECT * FROM `leave` WHERE `leave`.`status` = 0;";
      $stmt = $con->prepare($query);
      $stmt->execute();
      $pending_leave = $stmt->rowCount();

      //Summary Table
      echo "
            <div class='row text-center d-flex justify-content-center mt-3'>
              <h3 class='text-center'>Summary</h3>
          
              <div class='col-md-4 mt-2'>
                <div class='d card text-white bg-primary boxshadow'>
                  <div class='card-body row d-flex'>
                    <div class='card-title col'>
                      <p class='card-text fs-5'> $employee</p>
                      <h6>Total <br> employee</h6>
                    </div>
                    <div class='col d-flex flex-column pt-3'>
                      <i class='fa-solid fa-users fs-4'> </i>
                      <a class='fs-5 mt-1 text-white ' href='employee_read.php'> ViewList</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class='col-md-4 mt-2'>
              <div class='d card text-white bg-primary boxshadow'>
                <div class='card-body row d-flex'>
                  <div class='card-title col'>
                    <p class='card-text fs-5'> $pending_leave</p>
                    <h6>Total <br>Pending Leave</h6>
                  </div>
                  <div class='col d-flex flex-column pt-3'>
                    <i class='fa-solid fa-users fs-4'> </i>
                    <a class='fs-5 mt-1 text-white ' href='leave_read.php'> ViewList</a>
                  </div>
                </div>
              </div>
            </div>
              
          "; ?>


      <?php include 'script.php'; ?>

</body>

</html>