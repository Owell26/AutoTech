<?php
session_start();
include '../database/dbconn.php';

if (isset($_POST['sendFeedback'])) {
    $user_id = $_SESSION['user_id'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO feedback (user_id, subject, message) VALUES ('$user_id', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['status'] = 'success';
        $_SESSION['msg'] = 'Your feedback has been sent! Thank you for helping us improve.';
        header("Location: ../feedback.php");
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['msg'] = 'Failed to send feedback. Please try again.';
        header("Location: ../feedback.php");
    }
    exit();
}

// Admin logic to update feedback status
if (isset($_GET['update_status']) && $_SESSION['role'] === 'admin') {
    $f_id = mysqli_real_escape_string($conn, $_GET['id']);
    $new_status = mysqli_real_escape_string($conn, $_GET['status_val']);

    $sql = "UPDATE feedback SET status = '$new_status' WHERE feedback_id = '$f_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['status'] = 'success';
        $_SESSION['msg'] = 'Feedback status updated.';
        header("Location: ../admin-dashboard.php");
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['msg'] = 'Failed to update feedback status.';
        header("Location: ../admin-dashboard.php");
    }
    exit();
}
