<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['faculty_id'])) {
    header("Location: index.html");
    exit();
}

$faculty_id = $_SESSION['faculty_id'];

// Fetch Faculty's Name
$query = "SELECT name FROM faculty WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Error preparing statement for fetching name: " . $conn->error);
}
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
$faculty = $result->fetch_assoc();
$faculty_name = $faculty['name'] ?? 'Faculty';
$stmt->close();

// Fetch Faculty's Rating Summary
$sql = "SELECT 
           AVG(teaching_skills) AS avg_teaching,
           AVG(ppt_usage) AS avg_ppt,
           AVG(learning_effectiveness) AS avg_learning,
           AVG(overall_rating) AS avg_overall,
           COUNT(*) AS total_ratings
        FROM ratings 
        WHERE faculty_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement for fetching ratings: " . $conn->error);
}
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../UI/styles.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            background-color: #2C3E50;
            padding: 20px;
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #1abc9c;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .sidebar a:hover {
            background-color: #1abc9c;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f0f4f7;
        }

        .card {
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Welcome, <?php echo htmlspecialchars($faculty_name); ?></h2>
        <a href="faculty_dashboard.php">🏠 Dashboard</a>
        <a href="apply_leave.php">📅 Apply for Leave</a>
        <a href="view_leave_status.php">✅ View Leave Status</a>
        <a href="logout.php">🔓 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="card">
            <h1>Your Rating Summary</h1>
            <p><strong>Teaching Skills:</strong> <?php echo round($row['avg_teaching'], 2); ?>/5</p>
            <p><strong>PPT Usage:</strong> <?php echo round($row['avg_ppt'], 2); ?>/5</p>
            <p><strong>Learning Effectiveness:</strong> <?php echo round($row['avg_learning'], 2); ?>/5</p>
            <p><strong>Overall Rating:</strong> <?php echo round($row['avg_overall'], 2); ?>/5</p>
            <p><strong>Total Ratings Received:</strong> <?php echo $row['total_ratings']; ?></p>
        </div>
    </div>
</body>
</html>
