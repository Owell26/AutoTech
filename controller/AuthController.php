<?php
session_start();
include '../database/dbconn.php';

// Logout Logic
if (isset($_GET['logout'])) {
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        mysqli_query($conn, "DELETE FROM user_tokens WHERE token = '$token'");
        setcookie('remember_token', '', time() - 3600, '/');
    }
    session_destroy();
    header("Location: ../login.php");
    exit();
}

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
        $_SESSION['status'] = 'error';
        $_SESSION['msg'] = 'Email already exists';
        header("Location: ../register.php");
        exit();
    } else {
        $sql = "INSERT INTO users (fullname, email, password, role, course, year_level, section) VALUES ('$fullname', '$email', '$password', '$role', '$course', '$year_level', '$section')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['status'] = 'success';
            $_SESSION['msg'] = 'Registration successful! Please login.';
            header("Location: ../login.php");
            exit();
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['msg'] = 'Registration failed';
            header("Location: ../register.php");
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
            
            // Remember Me Logic
            if (isset($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)); // 30 days
                $u_id = $user['user_id'];
                
                $token_sql = "INSERT INTO user_tokens (user_id, token, expiry) VALUES ('$u_id', '$token', '$expiry')";
                if (mysqli_query($conn, $token_sql)) {
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
                }
            }

            $_SESSION['status'] = 'success';
            $_SESSION['msg'] = 'Welcome back, ' . $user['fullname'] . '!';

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: ../admin-dashboard");
                exit();
            }

            // Store student info in session if role is student
            if ($user['role'] == 'student') {
                $_SESSION['course'] = $user['course'];
                $_SESSION['year_level'] = $user['year_level'];
                $_SESSION['section'] = $user['section'];
            }

            header("Location: ../dashboard");
            exit();
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['msg'] = 'Invalid password';
            header("Location: ../login");
            exit();
        }
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['msg'] = 'Email not found';
        header("Location: ../login");
        exit();
    }
}

// Logout Logic
if (isset($_GET['logout'])) {
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        mysqli_query($conn, "DELETE FROM user_tokens WHERE token = '$token'");
        setcookie('remember_token', '', time() - 3600, '/');
    }
    session_destroy();
    header("Location: ../login");
    exit();
}
?>
