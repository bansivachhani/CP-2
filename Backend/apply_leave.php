<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_type = trim($_POST['leave_type']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $reason = trim($_POST['reason']);
    $username = $_SESSION['username'];

    // Validate input
    if (empty($leave_type) || empty($start_date) || empty($end_date) || empty($reason)) {
        $message = "All fields are required.";
    } else {
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO leave_applications (username, leave_type, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("sssss", $username, $leave_type, $start_date, $end_date, $reason);

        if ($stmt->execute()) {
            $message = "Leave application submitted successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Leave</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: auto;
        }
        h2 {
            color: #333;
        }
        input, textarea, select, button {
            margin: 5px 0;
            padding: 8px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Apply for Leave</h2>
        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
        <form method="POST" action="apply_leave.php">
            <label>Leave Type:</label>
            <select name="leave_type" required>
                <option value="Casual Leave">Casual Leave</option>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Paternity Leave">Paternity Leave</option>
                <option value="Other">Other</option>
            </select>
            <label>Start Date:</label>
            <input type="date" name="start_date" required>
            <label>End Date:</label>
            <input type="date" name="end_date" required>
            <label>Reason:</label>
            <textarea name="reason" rows="4" required></textarea>
            <button type="submit">Apply</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
