<?php
session_start();


$user_id = isset($_GET['user_id']) ? $_GET['user_id'] :  die('ERROR: Record ID not found.');
include 'config/database.php';

// user's ID stored in the session. 
try {
    $check_user = "SELECT employee.user_id, employee.username
    FROM employee
    INNER JOIN `LEAVE` ON employee.user_id = `LEAVE`.user_id
    WHERE employee.user_id = 104;
    ";
    $stmt = $con->prepare($check_user);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $stmt->rowCount();

    $flag =0;
    if ($count > 0) {
        header('Location: employee_read.php?action=faildelete');
        $flag =1;
    }

    if ($_SESSION['user_id'] == $user_id) {
        header('Location: employee_read.php?action=faildeleteown');
        $flag =1;
    }

    if ($flag == 0) {
        // delete query
        $query = "DELETE FROM employee WHERE user_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $user_id);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();

            if ($row['image'] != 'profile_default.jpg') {
                unlink("uploads/employee/" . $row['image']);
            }
            header('Location: employee_read.php?action=deleted');
        } else {
            header('Location: employee_read.php?action=faildelete');
        }
    }
}

// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
