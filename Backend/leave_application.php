<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION['faculty_name'])) {
    header("Location: ../UI/index.html");
    exit();
}

$faculty_id = $_SESSION['faculty_id'];

// Fetch total leaves taken
$query = "SELECT total_leaves_taken, paternity_leaves_taken FROM faculty WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
$faculty = $result->fetch_assoc();
$total_leaves = $faculty['total_leaves_taken'];
$paternity_leaves = $faculty['paternity_leaves_taken'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Leave</title>
    <link rel="stylesheet" href="../UI/styles.css">
</head>
<body>
    <div class="container">
        <h1>Apply for Leave</h1>
        <p>Total Leaves Taken: <?php echo $total_leaves; ?>/10</p>
        <form action="submit_leave.php" method="POST">
            <label for="leave_type">Leave Type:</label>
            <select name="leave_type" required>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Casual Leave">Casual Leave</option>
                <option value="Vacation Leave">Vacation Leave</option>
                <option value="Paternity Leave">Paternity Leave</option>
                <option value="Other">Other</option>
            </select>

            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>

            <label for="reason">Reason:</label>
            <textarea name="reason" required></textarea>

            <button type="submit">Submit Leave Request</button>
        </form>
    </div>
</body>
</html>
