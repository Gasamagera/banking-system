<?php
$host = "localhost";
$dbname = "bank_system";     // your database name
$username = "root";          // your DB username (often 'root' for localhost)
$password = "";              // your DB password (blank by default in XAMPP)

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
