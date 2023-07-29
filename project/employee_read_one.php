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

<?php
// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die('ERROR: Record ID not found.');

//include database connection
include 'config/database.php';

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
   extract($row);
    // shorter way to do that is extract($row)
}

// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>

<body>
    <?php include 'topnav.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>User Profile</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="userTable">
                          
                            <tr>
                                <td>Username</td>
                                <td><?php echo htmlspecialchars($username, ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <td>Profile Image</td>
                                <td>
                                    <img src="uploads/employee/<?php echo htmlspecialchars($image, ENT_QUOTES); ?>" class="profile-image w-25">
                                </td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td><?php echo htmlspecialchars($department, ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <td>First name</td>
                                <td><?php echo htmlspecialchars($firstname, ENT_QUOTES); ?></td>
                            </tr>

                            <tr>
                                <td>Last name</td>
                                <td><?php echo htmlspecialchars($lastname, ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td><?php echo htmlspecialchars($gender, ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <td>Birth Date</td>
                                <td><?php echo htmlspecialchars($datebirth, ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <td>Registration Date</td>
                                <td><?php echo htmlspecialchars($registration_dt, ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <td>Leave Balance</td>
                                <td><?php echo htmlspecialchars($leave_bal, ENT_QUOTES);  ?></td>
                            </tr>
                            <tr>
                                <td>Account Status</td>
                                <td><?php echo htmlspecialchars($accstatus, ENT_QUOTES); ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <a href='employee_update.php?user_id=<?php echo $user_id; ?>' class='btn btn-primary '>Edit <i class='fas fa-pen'></i></a>
                                    <a href='#' onclick='printTable()' class='btn btn-primary m-b-1em my-3 mx-2'>Print Table <i class="fa-solid fa-print"></i></a>
                                    <a href='employee_read.php' class='btn btn-secondary '><i class="fa-solid fa-circle-arrow-left"></i> Back to read employee </a>
                               
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Font Awesome CSS link for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 

    <?php include 'script.php'; ?>

</body>

</html>