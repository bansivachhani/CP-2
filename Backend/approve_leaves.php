<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.html");
    exit();
}

// Fetch Pending Leave Requests
$sql_pending = "SELECT lr.*, f.name AS faculty_name 
                FROM leave_requests lr
                JOIN faculty f ON lr.faculty_id = f.id
                WHERE lr.status = 'Pending'";
$result_pending = $conn->query($sql_pending);

// Fetch Processed Leave Requests
$sql_processed = "SELECT lr.*, f.name AS faculty_name 
                  FROM leave_requests lr
                  JOIN faculty f ON lr.faculty_id = f.id
                  WHERE lr.status != 'Pending'";
$result_processed = $conn->query($sql_processed);

// Process Leave Request
if (isset($_POST['approve']) || isset($_POST['reject'])) {
    $leave_id = $_POST['leave_id'];
    $status = isset($_POST['approve']) ? 'Approved' : 'Rejected';
    
    $stmt = $conn->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $leave_id);
    $stmt->execute();
    $stmt->close();

    header("Location: approve_leaves.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Leave Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
        }
        .container {
            margin-top: 30px;
        }
        table {
            background: white;
            border-radius: 5px;
            overflow: hidden;
        }
        th {
            background-color: #2C3E50;
            color: white;
            padding: 10px;
        }
        td {
            padding: 10px;
            text-align: center;
        }
        .btn-approve {
            background-color: #28a745;
            color: white;
        }
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Pending Leave Requests</h2>
    <?php if ($result_pending->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Faculty Name</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_pending->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['faculty_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="leave_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="approve" class="btn btn-approve btn-sm">Approve</button>
                                <button type="submit" name="reject" class="btn btn-reject btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending leave requests.</p>
    <?php endif; ?>

    <h2>Processed Leave Requests</h2>
    <?php if ($result_processed->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Faculty Name</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_processed->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['faculty_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                        <td>
                            <?php 
                                if ($row['status'] === 'Approved') {
                                    echo "<span style='color: green;'>Approved</span>";
                                } else {
                                    echo "<span style='color: red;'>Rejected</span>";
                                }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No processed leave requests found.</p>
    <?php endif; ?>
</div>
</body>
</html>
