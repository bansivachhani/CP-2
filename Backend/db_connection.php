<?php
$servername = "localhost";
$username = "root";    // Your MySQL username
$password = "";        // Your MySQL password (leave empty if not set)
$dbname = "faculty_leave_system";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
