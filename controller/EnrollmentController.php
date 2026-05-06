<?php
session_start();
include '../database/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Handle Enrollment via Code (Student)
if (isset($_POST['joinWithCode'])) {
    $moduleCode = mysqli_real_escape_string($conn, trim($_POST['moduleCode']));
    
    if ($_SESSION['role'] !== 'student') {
        header("Location: ../dashboard.php?status=error&msg=Only students can join modules.");
        exit();
    }

    // Find module by code
    $sql = "SELECT module_id FROM module WHERE module_code = '$moduleCode' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $module = mysqli_fetch_assoc($result);
        $moduleId = $module['module_id'];

        // Check if already enrolled
        $check = "SELECT enrollment_id FROM enrollments WHERE user_id = '$userId' AND module_id = '$moduleId'";
        $check_run = mysqli_query($conn, $check);

        if (mysqli_num_rows($check_run) == 0) {
            $enroll_sql = "INSERT INTO enrollments (user_id, module_id, status) VALUES ('$userId', '$moduleId', 'pending')";
            if (mysqli_query($conn, $enroll_sql)) {
                header("Location: ../dashboard.php?status=success&msg=Joined module! Waiting for instructor approval.");
            } else {
                header("Location: ../dashboard.php?status=error&msg=Failed to join module.");
            }
        } else {
            header("Location: ../dashboard.php?status=error&msg=You are already in this module.");
        }
    } else {
        header("Location: ../dashboard.php?status=error&msg=Invalid module code.");
    }
    exit();
}

// Handle Enrollment Request (Student)
if (isset($_GET['enroll'])) {
    $moduleId = mysqli_real_escape_string($conn, $_GET['enroll']);
    
    // Check if already enrolled
    $check = "SELECT enrollment_id FROM enrollments WHERE user_id = '$userId' AND module_id = '$moduleId'";
    $check_run = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($check_run) == 0) {
        $sql = "INSERT INTO enrollments (user_id, module_id, status) VALUES ('$userId', '$moduleId', 'pending')";
        if (mysqli_query($conn, $sql)) {
            header("Location: ../dashboard.php?status=success&msg=Enrollment request sent!");
        } else {
            header("Location: ../dashboard.php?status=error&msg=Failed to send request.");
        }
    } else {
        header("Location: ../dashboard.php?status=error&msg=You have already requested enrollment.");
    }
    exit();
}

// Handle Enrollment Approval/Rejection/Removal (Instructor)
if (isset($_GET['approve']) || isset($_GET['reject']) || isset($_GET['remove'])) {
    if ($_SESSION['role'] !== 'instructor') {
        header("Location: ../dashboard.php");
        exit();
    }
    
    $modId = isset($_GET['mod_id']) ? mysqli_real_escape_string($conn, $_GET['mod_id']) : null;
    $from = isset($_GET['from']) ? $_GET['from'] : null;
    
    if ($from == 'students') {
        $redirect = "../students.php" . ($modId ? "?module_id=$modId" : "");
    } else {
        $redirect = $modId ? "../view-module.php?id=$modId" : "../dashboard.php";
    }

    if (isset($_GET['approve'])) {
        $enrollmentId = mysqli_real_escape_string($conn, $_GET['approve']);
        $sql = "UPDATE enrollments SET status = 'accepted' WHERE enrollment_id = '$enrollmentId'";
        $msg = "Enrollment accepted";
    } elseif (isset($_GET['reject'])) {
        $enrollmentId = mysqli_real_escape_string($conn, $_GET['reject']);
        $sql = "UPDATE enrollments SET status = 'rejected' WHERE enrollment_id = '$enrollmentId'";
        $msg = "Enrollment rejected";
    } elseif (isset($_GET['remove'])) {
        $enrollmentId = mysqli_real_escape_string($conn, $_GET['remove']);
        $sql = "DELETE FROM enrollments WHERE enrollment_id = '$enrollmentId'";
        $msg = "Student removed from module";
    }
    
    if (mysqli_query($conn, $sql)) {
        $connector = (strpos($redirect, '?') === false) ? '?' : '&';
        header("Location: $redirect" . $connector . "status=success&msg=$msg");
    } else {
        $connector = (strpos($redirect, '?') === false) ? '?' : '&';
        header("Location: $redirect" . $connector . "status=error&msg=Action failed.");
    }
    exit();
}
?>
