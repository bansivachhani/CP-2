<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: index.html");
    exit();
}

$faculty_id = $_SESSION['faculty_id'];
$leave_type = $_POST['leave_type'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$reason = $_POST['reason'];

$sql = "INSERT INTO leave_requests (faculty_id, leave_type, start_date, end_date, reason, status) 
        VALUES (?, ?, ?, ?, ?, 'Pending')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $faculty_id, $leave_type, $start_date, $end_date, $reason);

if ($stmt->execute()) {
    echo "Leave request submitted successfully.";
} else {
    echo "Error submitting leave request: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
