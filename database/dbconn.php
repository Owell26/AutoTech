<?php
// Database connection configuration
$host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$port = 4000;
$dbname = "auto_tech_classroom";
$username = '4R2F6LvY6n72wzH.root';
$password = 'tSsML6qayt3hZAFR';

// Initialize MySQLi
$conn = mysqli_init();

// Enable SSL
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// Establish connection
if (!mysqli_real_connect($conn, $host, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Connection failed: " . mysqli_connect_error());
}

// $host = 'localhost';
// $dbname = "auto_tech_classroom";
// $username = 'root';
// $password = '';

// // Initialize MySQLi
// $conn = mysqli_connect($host, $username, $password, $dbname);

// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }
