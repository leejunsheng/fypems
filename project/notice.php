
<!DOCTYPE html>
<html>

<head>
    <title>Create Notice</title>
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
    <div class="container-fluid px-0">
        <div class="container">

            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $comment_subject = $_POST['subject'];
                $comment_text = $_POST['comment'];
                $create_date = date('Y-m-d', strtotime('+7 days'));
                
                $error_msg = "";
                if ($comment_subject == "") {
                    $error_msg .= "<div>Please make sure the subject is not empty.</div>";
                }

                if ($comment_text == "") {
                    $error_msg .= "<div>Please make sure the comment is not empty.</div>";
                }

                if (!empty($error_msg)) {
                    echo "<div class='alert alert-danger'>{$error_msg}</div>";
                } else {
                    try {
                        // insert query
                        $query = "INSERT INTO comments (comment_subject, comment_text,create_date) VALUES (:comment_subject, :comment_text,:create_date)";

                        // Prepare the statement
                        $stmt = $con->prepare($query);

                        // Bind parameters
                        $stmt->bindParam(':comment_subject', $comment_subject);
                        $stmt->bindParam(':comment_text', $comment_text);
                        $stmt->bindParam(':create_date', $create_date);

                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Notification added successfully.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }
                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
            }
            ?>

            <div class="card" style="border-radius: 15px; ">
                <div class="card-body p-4 p-md-5 ">
                    <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Create Announcement</h3>
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data" ">
                        <div class="form-group">
                            <label>Enter Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Enter Comment</label>
                            <textarea name="comment" id="comment" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <input type="submit" name="post" id="post" class="btn btn-info" value="Add" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </section>
    <?php include 'script.php'; ?>
</body>

</html>
