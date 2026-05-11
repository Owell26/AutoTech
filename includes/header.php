<?php 
session_start();
include 'database/dbconn.php';

// Fetch System Settings
$settings_sql = "SELECT * FROM system_settings LIMIT 1";
$settings_run = mysqli_query($conn, $settings_sql);
$sys = mysqli_fetch_assoc($settings_run);
$system_name = $sys['system_name'] ?? 'AutoTech';
$system_logo = $sys['system_logo'] ?? null;

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