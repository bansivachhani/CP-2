<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.html");
    exit();
}

if (!isset($_POST['subject_id'])) {
    echo "Subject not selected.";
    exit();
}

$subject_id = $_POST['subject_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rate Faculty</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f4f7;
        }
        .container {
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Rate Faculty</h2>
        <form action="../Backend/submit_rating.php" method="POST">
    <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">

    <div>
        <label>Teaching Skills (1-5):</label>
        <input type="number" name="teaching_skills" min="1" max="5" required>
    </div>

    <div>
        <label>PPT Usage (1-5):</label>
        <input type="number" name="ppt_usage" min="1" max="5" required>
    </div>

    <div>
        <label>Learning Effectiveness (1-5):</label>
        <input type="number" name="learning_effectiveness" min="1" max="5" required>
    </div>

    <div>
        <label>Overall Rating (1-5):</label>
        <input type="number" name="overall_rating" min="1" max="5" required>
    </div>

    <div>
        <label>Comments:</label>
        <textarea name="comments"></textarea>
    </div>

    <button type="submit">Submit Rating</button>
</form>

    </div>
</body>
</html>
