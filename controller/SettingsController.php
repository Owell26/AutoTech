<?php
session_start();
include '../database/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Handle Profile Update
if (isset($_POST['updateProfile'])) {
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    
    $update_sql = "UPDATE users SET fullname = '$fullname', email = '$email'";
    
    if ($role == 'student') {
        $course = mysqli_real_escape_string($conn, $_POST['course']);
        $year_level = mysqli_real_escape_string($conn, $_POST['year_level']);
        $section = mysqli_real_escape_string($conn, $_POST['section']);
        $update_sql .= ", course = '$course', year_level = '$year_level', section = '$section'";
    }
    
    $update_sql .= " WHERE user_id = '$userId'";
    
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['fullname'] = $fullname;
        if ($role == 'student') {
            $_SESSION['course'] = $course;
            $_SESSION['year_level'] = $year_level;
            $_SESSION['section'] = $section;
        }
        header("Location: ../settings.php?status=success&msg=Profile updated successfully#profile");
    } else {
        header("Location: ../settings.php?status=error&msg=Update failed#profile");
    }
    exit();
}

// Handle Profile Photo Upload
if (isset($_POST['updatePhoto'])) {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $target_dir = "../uploads/profile_pics/";
        
        // Ensure directory exists
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = $_FILES['profile_pic']['name'];
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (in_array($file_ext, $allowed_ext)) {
            // New unique name for the file
            $new_file_name = "profile_" . $userId . "_" . time() . "." . $file_ext;
            $target_path = $target_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $target_path)) {
                // Update database
                $sql = "UPDATE users SET profile_pic = '$new_file_name' WHERE user_id = '$userId'";
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['profile_pic'] = $new_file_name;
                    header("Location: ../settings.php?status=success&msg=Profile photo updated!#profile");
                } else {
                    header("Location: ../settings.php?status=error&msg=Database error while saving photo#profile");
                }
            } else {
                header("Location: ../settings.php?status=error&msg=Failed to move uploaded file#profile");
            }
        } else {
            header("Location: ../settings.php?status=error&msg=Only JPG, JPEG, & PNG files are allowed#profile");
        }
    } else {
        header("Location: ../settings.php?status=error&msg=No file selected or upload error#profile");
    }
    exit();
}

// Handle Password Change
if (isset($_POST['updatePassword'])) {
    $current_pass = trim($_POST['current_password']);
    $new_pass = trim($_POST['new_password']);
    $confirm_pass = trim($_POST['confirm_password']);
    
    // Check current password
    $check_sql = "SELECT password FROM users WHERE user_id = '$userId'";
    $user = mysqli_fetch_assoc(mysqli_query($conn, $check_sql));
    
    if ($current_pass !== $user['password']) {
        header("Location: ../settings.php?status=error&msg=Current password is incorrect#security");
        exit();
    }
    
    if ($new_pass !== $confirm_pass) {
        header("Location: ../settings.php?status=error&msg=New passwords do not match#security");
        exit();
    }
    
    $update_pass = "UPDATE users SET password = '$new_pass' WHERE user_id = '$userId'";
    if (mysqli_query($conn, $update_pass)) {
        header("Location: ../settings.php?status=success&msg=Password changed successfully#security");
    } else {
        header("Location: ../settings.php?status=error&msg=Failed to update password#security");
    }
    exit();
}
?>
