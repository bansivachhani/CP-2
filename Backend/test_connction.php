<?php
$conn = new mysqli("localhost", "root", "", "faculty_leave_system");
if ($conn->connect_error) {
    die("Failed: " . $conn->connect_error);
}
echo "Database Connected Successfully!";
?>
