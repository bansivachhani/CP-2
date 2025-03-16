<?php
session_start();
include("db_connection.php");

if (!isset($_POST['role']) || !isset($_POST['email']) || !isset($_POST['password'])) {
    die("Invalid Request");
}

$role = trim($_POST['role']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);

if ($role === 'student') {
    $query = "SELECT * FROM students WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['student_id'] = $user['id'];
        $_SESSION['student_name'] = $user['name'];
        $_SESSION['semester'] = $user['semester'];
        header("Location: student_panel.php");
        exit();
    } else {
        echo "Invalid Student Credentials";
    }
}

elseif ($role === 'faculty') {
    $query = "SELECT * FROM faculty WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['faculty_id'] = $user['id'];
        $_SESSION['faculty_name'] = $user['name'];
        header("Location: faculty_dashboard.php");
        exit();
    } else {
        echo "Invalid Faculty Credentials";
    }
}

elseif ($role === 'admin') {
    $query = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Invalid Admin Credentials";
    }
}

else {
    echo "Invalid Role Selection.";
}

mysqli_close($conn);
?>
