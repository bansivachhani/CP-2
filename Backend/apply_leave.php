<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: index.html");
    exit();
}

$faculty_id = $_SESSION['faculty_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Leave</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Apply for Leave</h2>
        <form action="submit_leave.php" method="POST">
            <div class="mb-3">
                <label>Leave Type:</label>
                <select name="leave_type" class="form-control" required>
                    <option value="Casual Leave">Casual Leave</option>
                    <option value="Sick Leave">Sick Leave</option>
                    <option value="Paternity Leave">Paternity Leave</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Start Date:</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>End Date:</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Reason:</label>
                <textarea name="reason" class="form-control" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Leave</button>
        </form>
    </div>
</body>
</html>
