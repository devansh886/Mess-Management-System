
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dm = "mess_database";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dm);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Set character encoding
$conn->set_charset("utf8mb4");

// Escape function (MySQLi compatible)
function escape($s) {
    global $conn;
    return $conn->real_escape_string($s);
}

?>
