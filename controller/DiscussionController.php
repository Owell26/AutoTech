<?php
session_start();
include '../database/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Post a New Comment or Reply
if (isset($_POST['postDiscussion'])) {
    $moduleId = mysqli_real_escape_string($conn, $_POST['module_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $parentId = isset($_POST['parent_id']) ? mysqli_real_escape_string($conn, $_POST['parent_id']) : 0;

    if (empty($message)) {
        header("Location: ../view-module.php?id=$moduleId&status=error&msg=Message cannot be empty");
        exit();
    }

    $sql = "INSERT INTO discussions (module_id, user_id, message, parent_id) VALUES ('$moduleId', '$userId', '$message', '$parentId')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../view-module.php?id=$moduleId&status=success&msg=Posted successfully#discussions");
    } else {
        header("Location: ../view-module.php?id=$moduleId&status=error&msg=Failed to post#discussions");
    }
    exit();
}

// Delete Discussion (Instructor or Owner only)
if (isset($_GET['delete_id'])) {
    $discussionId = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $moduleId = mysqli_real_escape_string($conn, $_GET['module_id']);
    
    // Authorization check
    $check_sql = "SELECT user_id FROM discussions WHERE discussion_id = '$discussionId' LIMIT 1";
    $check_res = mysqli_query($conn, $check_sql);
    $discussion = mysqli_fetch_assoc($check_res);

    if ($_SESSION['role'] == 'instructor' || $discussion['user_id'] == $userId) {
        $del_sql = "DELETE FROM discussions WHERE discussion_id = '$discussionId' OR parent_id = '$discussionId'";
        mysqli_query($conn, $del_sql);
        header("Location: ../view-module.php?id=$moduleId&status=success&msg=Post deleted#discussions");
    } else {
        header("Location: ../view-module.php?id=$moduleId&status=error&msg=Unauthorized#discussions");
    }
    exit();
}
?>
