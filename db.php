<?php
$host = "127.0.0.1"; // instead of localhost
$user = "root";
$password = ""; // XAMPP default blank password
$dbname = "sschool2-db";
$port = 3307; // default MySQL port

$conn = new mysqli($host, $user, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
