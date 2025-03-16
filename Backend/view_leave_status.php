<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: index.html");
    exit();
}

$faculty_id = $_SESSION['faculty_id'];

// Fetch all leave requests for the logged-in faculty
$sql = "SELECT * FROM leave_requests WHERE faculty_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Leave Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
        }
        .container {
            margin-top: 50px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Leave Status</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td>
                                <?php 
                                    if ($row['status'] === 'Approved') {
                                        echo "<span style='color: green;'>Approved</span>";
                                    } elseif ($row['status'] === 'Rejected') {
                                        echo "<span style='color: red;'>Rejected</span>";
                                    } else {
                                        echo "<span style='color: orange;'>Pending</span>";
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No leave requests found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
