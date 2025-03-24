<?php
session_start();
include("db_connection.php");

// Debugging: Check if session is set
if (!isset($_SESSION['student_id'])) {
    die("Session not set. Please log in again.");
}

$student_id = $_SESSION['student_id'];

// Debugging: Check Student ID
echo "Debug: Student ID - " . $student_id . "<br>";

$query = "SELECT ratings.*, subjects.subject_name, faculty.name AS faculty_name
          FROM ratings
          JOIN subjects ON ratings.subject_id = subjects.subject_id
          JOIN faculty ON subjects.faculty_id = faculty.faculty_id
          WHERE ratings.student_id = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Check if data exists
if ($result->num_rows == 0) {
    die("No ratings found for student_id: " . $student_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Your Ratings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Your Submitted Ratings</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
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
