<?php
session_start();
include '../database/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// CREATE ASSIGNMENT (Instructor Only)
if (isset($_POST['createAssignment'])) {
    if ($role !== 'instructor') {
        header("Location: ../dashboard.php");
        exit();
    }

    $moduleId = mysqli_real_escape_string($conn, $_POST['module_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);

    $sql = "INSERT INTO assignments (module_id, title, instructions, deadline) VALUES ('$moduleId', '$title', '$instructions', '$deadline')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../view-module.php?id=$moduleId&status=success&msg=Assignment created successfully!#assignments");
    } else {
        header("Location: ../view-module.php?id=$moduleId&status=error&msg=Failed to create assignment.#assignments");
    }
    exit();
}

// SUBMIT ASSIGNMENT (Student Only)
if (isset($_POST['submitAssignment'])) {
    if ($role !== 'student') {
        header("Location: ../dashboard.php");
        exit();
    }

    $assignmentId = mysqli_real_escape_string($conn, $_POST['assignment_id']);
    $moduleId = mysqli_real_escape_string($conn, $_POST['module_id']);
    $submissionText = mysqli_real_escape_string($conn, $_POST['submission_text']);
    
    $fileName = null;
    if (isset($_FILES['submissionFile']) && $_FILES['submissionFile']['error'] == 0) {
        $uploadDir = '../uploads/submissions/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . '_' . basename($_FILES['submissionFile']['name']);
        move_uploaded_file($_FILES['submissionFile']['tmp_name'], $uploadDir . $fileName);
    }

    $sql = "INSERT INTO submissions (assignment_id, user_id, submission_text, file_path) VALUES ('$assignmentId', '$userId', '$submissionText', '$fileName')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../view-module.php?id=$moduleId&status=success&msg=Assignment submitted successfully!#assignments");
    } else {
        header("Location: ../view-module.php?id=$moduleId&status=error&msg=Failed to submit assignment.#assignments");
    }
    exit();
}
?>
