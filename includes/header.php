<?php 
session_start();
include 'database/dbconn.php';

// Fetch System Settings
$settings_sql = "SELECT * FROM system_settings LIMIT 1";
$settings_run = mysqli_query($conn, $settings_sql);
$sys = mysqli_fetch_assoc($settings_run);
$system_name = $sys['system_name'] ?? 'AutoTech';
$system_logo = $sys['system_logo'] ?? null;

// Remember Me: Auto-login with Cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $now = date('Y-m-d H:i:s');
    
    $check_token_sql = "SELECT ut.*, u.* FROM user_tokens ut 
                        JOIN users u ON ut.user_id = u.user_id 
                        WHERE ut.token = '$token' AND ut.expiry > '$now' LIMIT 1";
    $token_result = mysqli_query($conn, $check_token_sql);
    
    if (mysqli_num_rows($token_result) > 0) {
        $user = mysqli_fetch_assoc($token_result);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        
        if ($user['role'] == 'student') {
            $_SESSION['course'] = $user['course'];
            $_SESSION['year_level'] = $user['year_level'];
            $_SESSION['section'] = $user['section'];
        }
    }
}

// Protect pages: redirect to login if not logged in and not on login/register pages
$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['user_id']) && !in_array($current_page, ['login.php', 'register.php'])) {
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $system_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  </head>
  <body>