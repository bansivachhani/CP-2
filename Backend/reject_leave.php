<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['admin_name'])) {
    header("Location: ../UI/index.html");
    exit();
}

if (isset($_GET['id'])) {
    $leave_id = $_GET['id'];

    // Update the leave status to Rejected
    $query = "UPDATE leave_requests SET status = 'Rejected' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $leave_id);

    if ($stmt->execute()) {
        echo "Leave Rejected Successfully!";
    } else {
        echo "Error Rejecting Leave!";
    }
}

header("Location: manage_leaves.php");
exit();
?>
