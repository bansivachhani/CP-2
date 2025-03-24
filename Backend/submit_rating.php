<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}

$student_id = $_SESSION['student_id'];
$subject_ids = $_POST['subject_id'];
$faculty_ids = $_POST['faculty_id'];  // Add this line to fetch faculty IDs
$teaching_skills = $_POST['teaching_skills'];
$ppt_usage = $_POST['ppt_usage'];
$learning_effectiveness = $_POST['learning_effectiveness'];
$overall_rating = $_POST['overall_rating'];
$comments = $_POST['comments'];

foreach ($subject_ids as $index => $subject_id) {
    $faculty_id = $faculty_ids[$index];  // Fetch corresponding faculty ID

    $query = "INSERT INTO ratings (student_id, faculty_id, subject_id, teaching_skills, ppt_usage, learning_effectiveness, overall_rating, comments, rating_date) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiiiiis", $student_id, $faculty_id, $subject_id, $teaching_skills[$index], $ppt_usage[$index], $learning_effectiveness[$index], $overall_rating[$index], $comments[$index]);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }
}

echo "Ratings submitted successfully.";

exit();
?>
