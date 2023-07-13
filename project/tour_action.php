<?php
// include database connection
include 'check_user_login.php';
$username = $_SESSION['login'];
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];
echo "Role: " . $role;
?>


<!-- PHP code to read records will be here -->
<?php

// include database connection
include 'config/database.php';

// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
$tour_id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
$action = isset($_GET['action']) ? $_GET['action'] : "";

// if it was redirected from delete.php
if ($action == 'approve') {
    $query = "UPDATE `tour` 
              SET status = 1
              WHERE tour_id = :tour_id";
    // prepare query for execution
    $stmt = $con->prepare($query);
    $stmt->bindParam(':tour_id', $tour_id);

    // Execute the query
    if ($stmt->execute()) {
        //echo "<div class='alert alert-success'>tour apply was approved.</div>";
        header("Location: tour_read.php?action=approved&id={$tour_id}");
    } else {
        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
    }
} elseif ($action == 'reject') {
    // Process the tour rejection
    $query = "UPDATE `tour` 
    SET status = 2
    WHERE tour_id = :tour_id";
    // prepare query for execution
    $stmt = $con->prepare($query);
    $stmt->bindParam(':tour_id', $tour_id);

    // Execute the query
    if ($stmt->execute()) {
        //echo "<div class='alert alert-success'>tour apply was rejected.</div>";
        header("Location: tour_read.php?action=rejected&id={$tour_id}");
    } else {
        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
    }
}
?>