<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "faculty") {
    header("Location: login.php");
    exit();
}

$faculty_id = $_SESSION["user_id"];

// Fetch faculty details
$sql = "SELECT name, email FROM faculty WHERE faculty_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
$faculty = $result->fetch_assoc();

echo "<h2>Welcome, Prof. {$faculty['name']} ({$faculty['email']})</h2>";
echo '<br><a href="logout.php">Logout</a>';
?>
