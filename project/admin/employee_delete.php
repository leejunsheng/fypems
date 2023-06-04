<?php
session_start();


$user_id = isset($_GET['user_id']) ? $_GET['user_id'] :  die('ERROR: Record ID not found.');
include '../config/database.php';

try {
    $check_user = "SELECT user_id, username AS check_user, image FROM employee INNER JOIN order_summary
         ON employee.username = order_summary.user WHERE employee.user_id = :user_id";
    $stmt = $con->prepare($check_user);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $stmt->rowCount();


    if ($count > 0) {
        header('Location: employee_read.php?action=faildelete');
    } else {
        // delete query
        $query = "DELETE FROM employee WHERE user_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $user_id);

        if ($stmt->execute()) {
            if ($row['image'] != 'profile_default.jpg') {
                unlink("uploads/employee/" . $row['image']);
            }
            header('Location: employee_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
}



// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
