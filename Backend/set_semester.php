<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}

if (isset($_POST['semester'])) {
    $_SESSION['semester'] = $_POST['semester'];
    header("Location: show_subjects.php");
} else {
    echo "Semester not selected.";
}
?>
