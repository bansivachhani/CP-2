<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.html");
    exit();
}

// Fetch all ratings with error handling
$query = "SELECT ratings.*, students.name AS student_name, subjects.subject_name, faculty.name AS faculty_name
          FROM ratings
          JOIN students ON ratings.student_id = students.id
          JOIN subjects ON ratings.subject_id = subjects.id
          JOIN faculty ON subjects.faculty_id = faculty.id";

$result = $conn->query($query);

// Check for SQL errors
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Ratings (Admin View)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
    <h2>All Ratings Submitted by Students</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Faculty</th>
                    <th>Teaching Skills</th>
                    <th>PPT Usage</th>
                    <th>Learning Effectiveness</th>
                    <th>Overall Rating</th>
                    <th>Comments</th>
                    <th>Rating Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo isset($row['student_name']) ? htmlspecialchars($row['student_name']) : "N/A"; ?></td>
                        <td><?php echo isset($row['subject_name']) ? htmlspecialchars($row['subject_name']) : "N/A"; ?></td>
                        <td><?php echo isset($row['faculty_name']) ? htmlspecialchars($row['faculty_name']) : "N/A"; ?></td>
                        <td><?php echo isset($row['teaching_skills']) ? $row['teaching_skills'] : "N/A"; ?></td>
                        <td><?php echo isset($row['ppt_usage']) ? $row['ppt_usage'] : "N/A"; ?></td>
                        <td><?php echo isset($row['learning_effectiveness']) ? $row['learning_effectiveness'] : "N/A"; ?></td>
                        <td><?php echo isset($row['overall_rating']) ? $row['overall_rating'] : "N/A"; ?></td>
                        <td><?php echo isset($row['comments']) ? htmlspecialchars($row['comments']) : "No Comments"; ?></td>
                        <td><?php echo isset($row['rating_date']) ? $row['rating_date'] : "N/A"; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No ratings found.</p>
    <?php endif; ?>
</div>
</body>
</html>
