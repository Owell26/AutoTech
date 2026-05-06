<?php
// Database connection configuration
$host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$dbname = "auto_tech_classroom";
$username = '4R2F6LvY6n72wzH.root';
$password = 'tSsML6qayt3hZAFR';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>