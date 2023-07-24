<?php
session_start();

$leave_id = isset($_GET['leave_id']) ? $_GET['leave_id'] :  die('ERROR: Record ID not found.');
include 'config/database.php';

try {

        // delete query
        $query = "DELETE FROM `leave` WHERE leave_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $leave_id);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();

            header('Location: leave_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }




// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
