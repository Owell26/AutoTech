<?php
// Database connection configuration
$host = 'localhost';
$dbname = "auto_tech_classroom";
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>