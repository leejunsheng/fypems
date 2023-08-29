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

$action = isset($_GET['action']) ? $_GET['action'] : "";

// Set the time zone to Malaysia (GMT+8)
$timezone = new DateTimeZone('Asia/Kuala_Lumpur');
$now = new DateTime('now', $timezone);

// Get the timestamp and date in Malaysia time
$timestamp = $now->format('Y-m-d H:i:s');
$date = $now->format('Y-m-d');



if ($action === "checkin") {
    // Check if the user has already checked in for the current date
    $checkinQuery = "SELECT * FROM checkinout WHERE user_id = :user_id AND date =:date";
    $checkinStmt = $con->prepare($checkinQuery);
    $checkinStmt->bindParam(':user_id', $uid);
    $checkinStmt->bindParam(':date', $date);
    $checkinStmt->execute();
    $row = $checkinStmt->fetch(PDO::FETCH_ASSOC);
    $count = $checkinStmt->rowCount();

    if ($count > 0) {
        header("Location: attendance.php?action=fail");
    } else {
        $query = "INSERT INTO checkinout (user_id, checkin_time, date) VALUES (:user_id, :timestamp, :date)";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':user_id', $uid);
        $stmt->bindParam(':timestamp', $timestamp);
        $stmt->bindParam(':date', $date);

        if ($stmt->execute()) {
            header("Location: attendance.php?action=checkin");
        }
    }
} elseif ($action === "checkout") {

    $checkoutQuery = "SELECT * FROM checkinout WHERE user_id = :user_id AND date = :date AND checkout_time IS NOT NULL";
    $checkoutQuery = $con->prepare($checkoutQuery);
    $checkoutQuery->bindParam(':user_id', $uid);
    $checkoutQuery->bindParam(':date', $date);
    $checkoutQuery->execute();
    $count = $checkoutQuery->rowCount();
    

    if ($count > 0) {
        header("Location: attendance.php?action=failout");
    } else {
        $query = "UPDATE checkinout SET checkout_time = :timestamp WHERE user_id = :user_id AND date = :date";
        $stmt1 = $con->prepare($query);
        $stmt1->bindParam(':timestamp', $timestamp);
        $stmt1->bindParam(':user_id', $uid);
        $stmt1->bindParam(':date', $date);

        if ($stmt1->execute()) {
            header("Location: attendance.php?action=checkout");
        }
    }
}


?>