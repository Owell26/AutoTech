<?php
session_start();
include '../database/dbconn.php';

// Registration Logic
if (isset($_POST['registerUser'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Student specific fields
    $course = isset($_POST['course']) ? mysqli_real_escape_string($conn, $_POST['course']) : null;
    $year_level = isset($_POST['year_level']) ? mysqli_real_escape_string($conn, $_POST['year_level']) : null;
    $section = isset($_POST['section']) ? mysqli_real_escape_string($conn, $_POST['section']) : null;

    // Check if email exists
    $check_email = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($check_email_run) > 0) {
        header("Location: ../register.php?error=Email already exists");
        exit();
    } else {
        $sql = "INSERT INTO users (fullname, email, password, role, course, year_level, section) VALUES ('$fullname', '$email', '$password', '$role', '$course', '$year_level', '$section')";
        if (mysqli_query($conn, $sql)) {
            header("Location: ../login.php?success=Registration successful! Please login.");
            exit();
        } else {
            header("Location: ../register.php?error=Registration failed");
            exit();
        }
    }
}

// Login Logic
if (isset($_POST['loginUser'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            
            // Store student info in session if role is student
            if ($user['role'] == 'student') {
                $_SESSION['course'] = $user['course'];
                $_SESSION['year_level'] = $user['year_level'];
                $_SESSION['section'] = $user['section'];
            }

            header("Location: ../dashboard.php");
            exit();
        } else {
            header("Location: ../login.php?error=Incorrect password");
            exit();
        }
    } else {
        header("Location: ../login.php?error=User not found");
        exit();
    }
}

// Logout Logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>
