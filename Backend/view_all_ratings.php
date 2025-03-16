<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.html");
    exit();
}

// Fetch all ratings
$query = "SELECT ratings.*, students.name AS student_name, subjects.subject_name, faculty.name AS faculty_name
          FROM ratings
          JOIN students ON ratings.student_id = students.id
          JOIN subjects ON ratings.subject_id = subjects.id
          JOIN faculty ON subjects.faculty_id = faculty.id";
          
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Ratings (Admin View)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>All Ratings Submitted by Students</h2>
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
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['faculty_name']); ?></td>
                    <td><?php echo $row['teaching_skills']; ?></td>
                    <td><?php echo $row['ppt_usage']; ?></td>
                    <td><?php echo $row['learning_effectiveness']; ?></td>
                    <td><?php echo $row['overall_rating']; ?></td>
                    <td><?php echo htmlspecialchars($row['comments']); ?></td>
                    <td><?php echo $row['rating_date']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
