<?php
// include database connection
include 'check_user_login.php';
$username = $_SESSION['login'];
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

?>


<!-- PHP code to read records will be here -->
<?php

// include database connection
include 'config/database.php';

// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not

$leave_id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
$action = isset($_GET['action']) ? $_GET['action'] : "";

if ($action == 'approve') {
    // Retrieve user_id and leave balance based on leave_id
    $getUserQuery = "SELECT user_id FROM `leave` WHERE leave_id = :leave_id";
    $getUserStmt = $con->prepare($getUserQuery);
    $getUserStmt->bindParam(':leave_id', $leave_id);

    // Execute the query to get user_id and leave balance
    if ($getUserStmt->execute()) {
        $row = $getUserStmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['user_id'];

        // Check leave balance before approving
        $checkBalanceQuery = "SELECT leave_bal FROM employee WHERE user_id = :user_id";
        $checkBalanceStmt = $con->prepare($checkBalanceQuery);
        $checkBalanceStmt->bindParam(':user_id', $user_id);

        if ($checkBalanceStmt->execute()) {
            $balanceRow = $checkBalanceStmt->fetch(PDO::FETCH_ASSOC);
            $leave_balance = $balanceRow['leave_bal'];

            // Check if leave balance is greater than 0
            if ($leave_balance > 0) {
                // Update employee leave balance
                $updateQuery = "UPDATE employee 
                            SET leave_bal = leave_bal - 1
                            WHERE user_id = :user_id";
                $updateStmt = $con->prepare($updateQuery);
                $updateStmt->bindParam(':user_id', $user_id);

                // Execute the query to update leave balance
                if ($updateStmt->execute()) {

                    // Update leave status
                    $approveQuery = "UPDATE `leave` 
                    SET status = 1
                    WHERE leave_id = :leave_id";
                    $approveStmt = $con->prepare($approveQuery);
                    $approveStmt->bindParam(':leave_id', $leave_id);

                    // Execute the query to update leave status
                    if ($approveStmt->execute()) {
                        header("Location: leave_read.php?action=approved&id={$leave_id}");
                    
                    } else {
                        header("Location: leave_read.php?action=statusfail");
                    }
                } else {
                    header("Location: leave_read.php?action=updatefail");
                }
            } else {
                header("Location: leave_read.php?action=leavebal");
            }
        } else {
            header("Location: leave_read.php?action=fetchfail");
        }
    } else {
        header("Location: leave_read.php?action=fetchfail");
    }

   

    
} elseif ($action == 'reject') {
    // Process the leave rejection
    $query = "UPDATE `leave` 
    SET status = 2
    WHERE leave_id = :leave_id";
    // prepare query for execution
    $stmt = $con->prepare($query);
    $stmt->bindParam(':leave_id', $leave_id);

    // Execute the query
    if ($stmt->execute()) {
        //echo "<div class='alert alert-success'>Leave apply was rejected.</div>";
        header("Location: leave_read.php?action=rejected&id={$leave_id}");
    } else {
        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
    }
}
?>