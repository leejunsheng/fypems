<?php
// Check if the user is logged in and if not, redirect to the login page
include 'check_user_login.php';

// Include database connection
include 'config/database.php';

// Get user ID and role from the session
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Set a flag to show success message after updating the password
$updated = false;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the posted data
    $old_password = $_POST['old_password'];
    $new_password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']);

    // Validate old password
    $query = "SELECT password FROM `employee` WHERE user_id=:uid";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stored_password = $row['password'];

    if (md5($old_password) !== $stored_password) {
        $error_msg = "<div class='alert alert-danger'>Wrong Old Password.</div>";
    } elseif ($new_password !== $confirm_password) {
        $error_msg = "<div class='alert alert-danger'>Confirm Password and New Password do not match.</div>";
    } elseif (strlen($new_password) < 8) {
        $error_msg .= "<div >Please make sure password less than 8 character.</div>";
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        $error_msg .= "<div >Please make sure password combine capital a-z.</div>";
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        $error_msg .= "<div >Please make sure password combine 0-9.</div>";
    } 
    
    if (!empty($error_msg)) {
        echo "<div class='alert alert-danger'>{$error_msg}</div>";
    }
    else {
        // Update the password in the database
        $query = "UPDATE employee SET password=:password WHERE user_id=:uid";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':uid', $uid);
        $stmt->bindParam(':password', $new_password);

        if ($stmt->execute()) {
            $updated = true;
        } else {
            $error_msg = "<div class='alert alert-danger'>Unable to change password. Please try again.</div>";
        }
    }
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Update Password</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>

<body>
    <?php include 'topnav.php'; ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Password</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($updated) : ?>
                            <div class='alert alert-success'>Password changed successfully.</div>
                        <?php elseif (isset($error_msg)) : ?>
                            <div class='alert alert-danger'><?php echo $error_msg; ?></div>
                        <?php endif; ?>

                        <!-- Password Update Form -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label for="old_password">Old Password</label>
                                <input type="password" name="old_password" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" name="password" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required />
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary mt-4">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'script.php'; ?>
</body>

</html>