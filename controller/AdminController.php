<?php
session_start();
include '../database/dbconn.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

if (isset($_POST['updateSettings'])) {
    $system_name = mysqli_real_escape_string($conn, $_POST['system_name']);
    $logo_name = null;

    // Handle Logo Upload
    if (isset($_FILES['system_logo']) && $_FILES['system_logo']['error'] === 0) {
        $target_dir = "../uploads/system/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES['system_logo']['name'], PATHINFO_EXTENSION));
        $new_logo_name = "logo_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_logo_name;

        if (move_uploaded_file($_FILES['system_logo']['tmp_name'], $target_file)) {
            $logo_name = $new_logo_name;
        }
    }

    // Update Query
    if ($logo_name) {
        $sql = "UPDATE system_settings SET system_name = '$system_name', system_logo = '$logo_name' WHERE id = 1";
    } else {
        $sql = "UPDATE system_settings SET system_name = '$system_name' WHERE id = 1";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: ../admin-dashboard.php?status=success&msg=System settings updated successfully!");
    } else {
        header("Location: ../admin-dashboard.php?status=error&msg=Failed to update settings.");
    }
    exit();
}
