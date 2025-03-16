<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Semester</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Select Your Semester</h2>
    <form action="set_semester.php" method="POST">
        <select name="semester" class="form-select" required>
            <option value="" disabled selected>Select Semester</option>
            <?php for ($i = 1; $i <= 8; $i++): ?>
                <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-primary mt-3">Proceed</button>
    </form>
</div>
</body>
</html>
