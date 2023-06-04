<?php
// used to connect to the database
$host = "localhost";
$db_name = "emsfyp";
$dbuser_name = "root";
$dbpass_word = "";

try {
   
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $dbuser_name, $dbpass_word);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // show error
}

// show error
catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
?>

