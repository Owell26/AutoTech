<?php
session_start();
include 'database/dbconn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Save the code to session to handle it after login
    if (isset($_GET['code'])) {
        $_SESSION['pending_module_code'] = $_GET['code'];
    }
    header("Location: login.php?msg=Please login to join the module");
    exit();
}

if (!isset($_GET['code'])) {
    header("Location: dashboard.php");
    exit();
}

$moduleCode = mysqli_real_escape_string($conn, $_GET['code']);
$userId = $_SESSION['user_id'];

// Check if role is student
if ($_SESSION['role'] !== 'student') {
    header("Location: dashboard.php?status=error&msg=Only students can join modules via link.");
    exit();
}

// Find module
$sql = "SELECT module_id, module_description FROM module WHERE module_code = '$moduleCode' LIMIT 1";
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
            header("Location: dashboard.php?status=success&msg=Request to join " . $module['module_description'] . " sent!");
        } else {
            header("Location: dashboard.php?status=error&msg=Failed to join module.");
        }
    } else {
        header("Location: dashboard.php?status=error&msg=You are already enrolled in this module.");
    }
} else {
    header("Location: dashboard.php?status=error&msg=Invalid invitation link.");
}
exit();
?>
