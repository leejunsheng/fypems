<?php
include 'config/database.php';

if (isset($_POST['view'])) {
    if ($_POST["view"] != '') {
        $query = "UPDATE comments SET comment_status = 1 WHERE comment_status = 0";
        $stmt = $conn->prepare($query);
    }

    $query = "SELECT * FROM comments ORDER BY comment_id DESC LIMIT 5";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $output = '';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $output .= '
                <li>
                    <a href="#">
                        <strong>'.$row["comment_subject"].'</strong><br />
                        <small><em>'.$row["comment_text"].'</em></small>
                    </a>
                </li>
            ';
        }
    } else {
        $output .= '<li><a href="#" class="text-bold text-italic">No Noti Found</a></li>';
    }

    $status_query = "SELECT * FROM comments WHERE comment_status = 0";
    $stmt_status = $conn->prepare($status_query);
    $stmt_status->execute();
    $result_status = $stmt_status->get_result();
    $count = $result_status->num_rows;

    $data = array(
        'notification' => $output,
        'unseen_notification' => $count
    );

    echo json_encode($data);
}
?>
