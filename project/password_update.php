<?php
include 'check_user_login.php';
// include database connection
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Password</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <?php include 'head.php'; ?>
</head>


<body>
    <?php include 'topnav.php'; ?>
    <div class="container-fluid px-0">
        <div class="container">
            <div class="page-header my-3">
                <h1>Update Password</h1>
            </div>
            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die('ERROR: Record ID not found.');

            //include database connection
            include 'config/database.php';

            $action = isset($_GET['action']) ? $_GET['action'] : "";

            // if it was redirected from delete.php
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }

            // read current record's data
            try {
                // prepare select query

                $query = "SELECT * FROM `employee` WHERE user_id='$uid'";
                $stmt = $con->prepare($query);

                // this is the first question mark
                $stmt->bindParam(1, $user_id);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $password = $row['password'];
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <?php
            // check if form was submitted
            if ($_POST) {
                $pass_word = ($_POST['password']);
                $old_password = md5($_POST['old_password']);
                $confirm_password = ($_POST['confirm_password']);
                $error_msg = "";

                if ($row['password'] == $old_password) {
                    if ($pass_word == "") {
                        $error_msg .= "<div>Please make sure password are not empty.</div>";
                    } elseif (strlen($pass_word) < 8) {
                        $error_msg .= "<div>Please make sure password more than 8 character. </div>";
                    } elseif (!preg_match('/[a-z]/', $pass_word)) {
                        $error_msg .= "<div> Please make sure password combine capital a-z. </div>";
                    } elseif (!preg_match('/[0-9]/', $pass_word)) {
                        $error_msg .= " <div> Please make sure password combine 0-9. </div>";
                    }

                    if ($old_password == $pass_word) {
                        $error_msg .= "<div>Please make sure Old Password cannot same with New Password.</div>";
                    }
                    if ($old_password != "" && $password != "" && $confirm_password == "") {
                        $error_msg .= "<div>Please make sure confirm password are not empty.</div>";
                    }
                    if ($pass_word != $confirm_password) {
                        $error_msg .= "<div>Please make sure Confirm Password and New Password are same.</div>";
                    }
                } else {
                    $error_msg .= "<div>Wrong Old Password.</div>";
                }

                if (!empty($error_msg)) {
                    echo "<div class='alert alert-danger'>{$error_msg}</div>";
                } else {
                    // include database connection
                    include 'config/database.php';
                    try {
                        // write update query
                        // in this case, it seemed like we have so many fields to pass and
                        // it is better to label them and not use question marks
                        $query = "UPDATE employee SET password=:password WHERE user_id = :user_id";
                        // prepare query for excecution
                        $stmt = $con->prepare($query);

                        // posted values
                        $password = htmlspecialchars(md5(strip_tags($_POST['password'])));

                        // bind the parameters
                        $stmt->bindParam(':user_id', $user_id);
                        $stmt->bindParam(':password', $password);

                        // Execute the query
                        if ($stmt->execute()) {
                            // echo "<div class='alert alert-success'>Password change success.</div>";
                            header("Location: index.php?update={$user_id}");
                        } else {
                            echo "<div class='alert alert-danger'>Unable to change password. Please try again.</div>";
                        }
                    }

                    // show errors
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
            } ?>

            <!--we have our html form here where new record information can be updated-->


            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?user_id={$user_id}"); ?>" method="post">
                <table class="table table-hover table-responsive table-bordered">
                    <tr>
                        <td>Old Password</td>
                        <td><input type="password" name="old_password" class="form-control" required /></td>
                    </tr>
                    <tr>
                        <td>New Password</td>
                        <td><input type="password" name="password" class="form-control" required /></td>
                    </tr>
                    <tr>
                        <td>Confirm Password</td>
                        <td><input type="password" name="confirm_password" class="form-control" required /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" value="change" class="btn btn-primary" />

                        </td>
                    </tr>
                </table>
            </form>


        </div>
        <!-- end .container -->
    </div>




    <?php include 'script.php'; ?>

</body>

</html>