<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['admin_name'])) {
    header("Location: ../UI/index.html");
    exit();
}

// Fetch all pending leave requests
$query = "SELECT * FROM leave_requests WHERE status = 'Pending'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Leave Requests</title>
    <link rel="stylesheet" href="../UI/styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Leave Requests</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Faculty ID</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['faculty_id']; ?></td>
                        <td><?php echo $row['leave_type']; ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                        <td><?php echo $row['end_date']; ?></td>
                        <td><?php echo $row['reason']; ?></td>
                        <td>
                            <a href="approve_leave.php?id=<?php echo $row['id']; ?>">✅ Approve</a> | 
                            <a href="reject_leave.php?id=<?php echo $row['id']; ?>">❌ Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No pending leave requests.</p>
        <?php endif; ?>
    </div>
</body>
</html>
