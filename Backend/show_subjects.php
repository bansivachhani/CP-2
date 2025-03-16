<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}

if (!isset($_SESSION['semester'])) {
    echo "Please select a semester first.";
    exit();
}

$semester = $_SESSION['semester'];
$student_id = $_SESSION['student_id'];

$query = "SELECT subjects.id, subjects.subject_name, faculty.name AS faculty_name 
          FROM subjects 
          LEFT JOIN faculty ON subjects.faculty_id = faculty.id
          WHERE subjects.semester = ?";
          
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $semester);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Subjects</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Rate Subjects for Semester <?php echo htmlspecialchars($semester); ?></h2>
    <form action="submit_rating.php" method="POST">

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="card mt-3 p-3">
            <h4><?php echo htmlspecialchars($row['subject_name']); ?> (Faculty: <?php echo htmlspecialchars($row['faculty_name']); ?>)</h4>
            <input type="hidden" name="subject_id[]" value="<?php echo $row['id']; ?>">

            <label>Teaching Skills (1-5):</label>
            <input type="number" name="teaching_skills[]" min="1" max="5" class="form-control" required>

            <label>PPT Usage (1-5):</label>
            <input type="number" name="ppt_usage[]" min="1" max="5" class="form-control" required>

            <label>Learning Effectiveness (1-5):</label>
            <input type="number" name="learning_effectiveness[]" min="1" max="5" class="form-control" required>

            <label>Overall Rating (1-5):</label>
            <input type="number" name="overall_rating[]" min="1" max="5" class="form-control" required>

            <label>Comments:</label>
            <textarea name="comments[]" rows="2" class="form-control"></textarea>
        </div>
    <?php } ?>

    <button type="submit" class="btn btn-primary mt-3">Submit Ratings</button>
</form>

</div>
</body>
</html>
