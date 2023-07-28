<!DOCTYPE html>
<html>

<head>
    <title>Read Notice</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>

<?php
include 'check_user_login.php';
include 'config/database.php';
?>

<body>
    <?php include 'topnav.php'; ?>
    <section class="h-100 py-3">
        <div class="container">
            <?php
            // Get current date
            $today = date('Y-m-d');

            // Query to fetch employees on leave today
            $query = "SELECT *
            FROM `leave` AS l
            JOIN `employee` AS e ON l.user_id = e.user_id
            WHERE ('$today' BETWEEN l.start_date AND l.end_date
                    OR '$today' = l.leave_date)
              AND l.status = 1";

            $stmt = $con->prepare($query);
            $stmt->execute();
            $leaveCount = $stmt->rowCount();

            // Query to fetch employees on tour today
            $query = "SELECT *
            FROM `tour` AS l
            JOIN `employee` AS e ON l.user_id = e.user_id
            WHERE ('$today' BETWEEN l.start_date AND l.end_date
                    OR '$today' = l.tour_date)
              AND l.status = 1";
            $stmt2 = $con->prepare($query);
            $stmt2->execute();
            $tourCount = $stmt2->rowCount();
            ?>
            <div class="text-end"><a href='#' onclick='printTable()' class='btn btn-primary m-b-1em my-3 mx-2 text-end'>Print Table <i class="fa-solid fa-print"></i></a></div>
            <div class='row' id='dataTable'>
                <div class='col'>
                    <?php
                    if ($leaveCount > 0) {
                        echo "<div class='card my-2' style='border-radius: 15px;'>
                                <div class='card-body'>
                                    <h3 class='mb-4'>Employees on Leave Today</h3>
                                    <table>
                                        <ul>";
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $firstname = $row['firstname'];
                            $lastname = $row['lastname'];
                            echo "<li>{$firstname} {$lastname}</li>";
                        }
                        echo "</ul>
                                    </table>
                                </div>
                            </div>";
                    } else {
                        echo "<div class='alert alert-info'>No employees on leave today.</div>";
                    }
                    ?>
                </div>

                <div class='col'>
                    <?php
                    if ($tourCount  > 0) {
                        echo "<div class='card my-2' style='border-radius: 15px;'>
                                <div class='card-body'>
                                    <h3 class='mb-4'>Employees on Work Tour Today.</h3>
                                    <table>
                                        <ul>";
                        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                            $firstname = $row['firstname'];
                            $lastname = $row['lastname'];
                            echo "<li>{$firstname} {$lastname}</li>";
                        }
                        echo "</ul>
                                    </table>
                                </div>
                            </div>";
                    } else {
                        echo "<div class='alert alert-info'>No employees on tour today.</div>";
                    }
                    ?>
                </div>
            </div>

            <?php
            // Fetch comments from the database
            $query = "SELECT * FROM comments";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($comments)) {
                foreach ($comments as $comment) {
                    $comment_subject = $comment['comment_subject'];
                    $comment_text = $comment['comment_text'];
                    $create_date = $comment['create_date'];

                    echo "
                    <div class='card my-2' style='border-radius: 15px;'>
                        <div class='card-body'>
                            <h3 class='card-title'>$comment_subject</h3>
                            <p class='card-text'>$comment_text</p>
                            <p class='card-text text-end'>Create Date: $create_date</p>
                        </div>
                    </div>";
                }
            } else {
                echo "<div class='alert alert-info'>No comments found.</div>";
            }
            ?>
        </div>
    </section>
    <?php include 'script.php'; ?>
</body>

</html>