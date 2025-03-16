<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.html");
    exit();
}

if (!isset($_POST['leave_id']) || !isset($_POST['action'])) {
    die("Invalid Request.");
}

$leave_id = $_POST['leave_id'];
$action = $_POST['action'];

$status = ($action === 'approve') ? 'Approved' : 'Rejected';

// Update the leave request status in the database
$query = "UPDATE leave_requests SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $leave_id);

if ($stmt->execute()) {
    header("Location: approve_leaves.php?message=Leave request has been $status successfully.");
} else {
    echo "Error updating leave status: " . $conn->error;
}

$stmt->close();
$conn->close();
