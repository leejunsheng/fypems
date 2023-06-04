<?php
// include database connection
include 'check_user_login.php';
?>



<!DOCTYPE HTML>
<html>

<head>
    <title>Employee Profile</title>
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
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die('ERROR: Record ID not found.');

            //include database connection
            include '../config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT * FROM employee WHERE user_id = :user_id";
                $stmt = $con->prepare($query);

                // Bind the parameter
                $stmt->bindParam(":user_id", $user_id);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $username = $row['username'];
                $image = $row['image'];
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $gender = $row['gender'];
                $datebirth = $row['datebirth'];
                $registration_dt = $row['registration_dt'];
                $role = $row['role'];
                $accstatus = $row['accstatus'];
                // shorter way to do that is extract($row)
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <!-- HTML read one record table will be here -->
            <!--we have our html table here where the record will be displayed-->
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Profile Image</td>
                    <td>
                        <div><img src="uploads/employee/<?php echo htmlspecialchars($image, ENT_QUOTES);  ?>" class="w-25 mb-2"></div>
                    </td>
                </tr>
                <tr>
                    <td>First name</td>
                    <td><?php echo htmlspecialchars($firstname, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Last name</td>
                    <td><?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Birth Date</td>
                    <td><?php echo htmlspecialchars($datebirth, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Registeration Data</td>
                    <td><?php echo htmlspecialchars($registration_dt, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td><?php echo htmlspecialchars($accstatus, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php echo "<a href='employee_update.php?user_id={$user_id}' class='btn btn-primary'>Edit <i class='fa-solid fa-pen-to-square'></i></a>"; ?>
                        <a href='employee_read.php' class='btn btn-secondary m-r-1em mx-2'>Back to read employee</a>
                    </td>
                </tr>
            </table>

        </div>
    </div>
    <!-- end .container -->

    <?php include 'script.php'; ?>
    
</body>

</html>