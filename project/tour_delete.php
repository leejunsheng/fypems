<?php
session_start();

$tour_id = isset($_GET['tour_id']) ? $_GET['tour_id'] :  die('ERROR: Record ID not found.');
include 'config/database.php';

try {

        // delete query
        $query = "DELETE FROM `tour` WHERE tour_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $tour_id);

        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();

            header('Location: tour_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }




// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
