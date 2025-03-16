<?php
session_start();
include("db_connection.php");

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.html");
    exit();
}

// Check if the form data is present
if (!isset($_POST['subject_id'])) {
    echo "Invalid form submission.";
    exit();
}

$student_id = $_SESSION['student_id'];
$subject_ids = $_POST['subject_id'];
$teaching_skills = $_POST['teaching_skills'];
$ppt_usages = $_POST['ppt_usage'];
$learning_effectiveness = $_POST['learning_effectiveness'];
$overall_ratings = $_POST['overall_rating'];
$comments = $_POST['comments'];

// Error tracking variable
$hasError = false;

// Insert each rating into the database
for ($i = 0; $i < count($subject_ids); $i++) {
    $subject_id = $subject_ids[$i];
    $teaching_skill = $teaching_skills[$i];
    $ppt_usage = $ppt_usages[$i];
    $learning_effect = $learning_effectiveness[$i];
    $overall_rating = $overall_ratings[$i];
    $comment = $comments[$i];

    $sql = "INSERT INTO ratings (student_id, subject_id, teaching_skills, ppt_usage, learning_effectiveness, overall_rating, comments) 
            VALUES ('$student_id', '$subject_id', '$teaching_skill', '$ppt_usage', '$learning_effect', '$overall_rating', '$comment')";

    if (!$conn->query($sql)) {
        $hasError = true;
        echo "Error: " . $conn->error;
    }
}

$conn->close();

if ($hasError) {
    echo "<br>There were errors in submitting some ratings. Please check your entries.";
} else {
    header("Location: student_panel.php?message=Ratings submitted successfully.");
    exit();
}
?>
