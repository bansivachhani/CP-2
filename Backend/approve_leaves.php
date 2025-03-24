<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in as Admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Approve or reject leave
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['leave_id']) && isset($_POST['action'])) {
    $leave_id = intval($_POST['leave_id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $status = 'Approved';
    } elseif ($action === 'reject') {
        $status = 'Rejected';
    } else {
        $status = 'Pending';
    }

    // Update leave status securely
    $stmt = $conn->prepare("UPDATE leave_applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $leave_id);

    if ($stmt->execute()) {
        $message = "Leave has been " . strtolower($status) . " successfully!";
    } else {
        $message = "Error updating leave status: " . $conn->error;
    }

    $stmt->close();
}

// Fetch all pending leave applications
$leaveApplications = [];
$query = "SELECT id, username, leave_type, start_date, end_date, reason, status FROM leave_applications WHERE status = 'Pending'";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $leaveApplications[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approve Leaves</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        button {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .approve {
            background-color: #28a745;
        }
        .reject {
            background-color: #dc3545;
        }
        .message {
            margin: 10px 0;
            color: green;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2>Pending Leave Applications</h2>
        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
        <table>
            <tr>
                <th>Username</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($leaveApplications as $leave) { ?>
            <tr>
                <td><?php echo htmlspecialchars($leave['username']); ?></td>
                <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                <td><?php echo htmlspecialchars($leave['start_date']); ?></td>
                <td><?php echo htmlspecialchars($leave['end_date']); ?></td>
                <td><?php echo htmlspecialchars($leave['reason']); ?></td>
                <td>
                    <form method="POST" action="approve_leaves.php" style="display: inline;">
                        <input type="hidden" name="leave_id" value="<?php echo $leave['id']; ?>">
                        <button class="approve" type="submit" name="action" value="approve">Approve</button>
                        <button class="reject" type="submit" name="action" value="reject">Reject</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
