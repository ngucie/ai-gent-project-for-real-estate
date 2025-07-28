<?php
$host = "localhost";
$dbname = "realestate_ai";
$user = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
