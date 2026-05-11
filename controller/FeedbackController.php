<?php
session_start();
include '../database/dbconn.php';

if (isset($_POST['sendFeedback'])) {
    $user_id = $_SESSION['user_id'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO feedback (user_id, subject, message) VALUES ('$user_id', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../feedback.php?status=success&msg=Your feedback has been sent! Thank you for helping us improve.");
    } else {
        header("Location: ../feedback.php?status=error&msg=Failed to send feedback. Please try again.");
    }
    exit();
}

// Admin logic to update feedback status
if (isset($_GET['update_status']) && $_SESSION['role'] === 'admin') {
    $f_id = mysqli_real_escape_string($conn, $_GET['id']);
    $new_status = mysqli_real_escape_string($conn, $_GET['status_val']);

    $sql = "UPDATE feedback SET status = '$new_status' WHERE feedback_id = '$f_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../admin-dashboard.php?status=success&msg=Feedback status updated.");
    } else {
        header("Location: ../admin-dashboard.php?status=error&msg=Failed to update feedback status.");
    }
    exit();
}
