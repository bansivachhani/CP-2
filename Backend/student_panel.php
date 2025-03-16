<!-- student_panel.php -->
<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.html");
    exit();
}

// Database connection
include("db_connection.php");

// Fetch student's name from the database
$student_id = $_SESSION['student_id'];
$sql = "SELECT name FROM students WHERE id = '$student_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_name = $row['name'];
} else {
    $student_name = "Student";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $student_name; ?>'s Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f0f4f7;
        }

        .sidebar {
            width: 250px;
            background-color: #3b4cb8;
            color: white;
            padding-top: 20px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            margin-bottom: 10px;
            transition: background-color 0.3s;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #2a378d;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
            height: 100vh;
            overflow-y: auto;
            background: white;
        }

        .header {
            font-size: 28px;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <h2><?php echo $student_name; ?>'s Panel</h2>
        <a href="select_semester.php"><i class="fas fa-university"></i> Select Semester</a>
        <a href="show_subjects.php"><i class="fas fa-star"></i> Rate Faculty</a>
        <a href="view_ratings.php"><i class="fas fa-chart-bar"></i> View Ratings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <div class="header">Welcome, <?php echo $student_name; ?>!</div>
        <p>Use the sidebar to navigate through the functionalities.</p>
    </div>
</body>
</html>
