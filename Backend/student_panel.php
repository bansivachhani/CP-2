<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "student") {
    header("Location: ../UI/index.html");
    exit();
}

include("db_connection.php");
$student_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student_name = ($result->num_rows > 0) ? $result->fetch_assoc()['name'] : "Student";

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($student_name); ?>'s Panel</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($student_name); ?>!</h1>
    <p>This is your Student Panel.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
