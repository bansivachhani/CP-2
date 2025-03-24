<?php
session_start();
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    if (empty($email) || empty($password) || empty($role)) {
        die("Please fill all fields!");
    }

    // Role-based table and column selection
    if ($role == "student") {
        $table = "students";
        $id_column = "student_id";
        $redirect = "student_panel.php";
    } elseif ($role == "faculty") {
        $table = "faculty";
        $id_column = "faculty_id";
        $redirect = "faculty_dashboard.php";
    } elseif ($role == "admin") {
        $table = "admin";
        $id_column = "admin_id";
        $redirect = "admin_dashboard.php";
    } else {
        die("Invalid Role Selected!");
    }

    // Prepare SQL Query
    $sql = "SELECT $id_column, password FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ✅ Direct string comparison (NO Hashing)
        if ($password === $row["password"]) {
            $_SESSION["user_id"] = $row[$id_column];
            $_SESSION["role"] = $role;
            
            header("Location: /Bansi/CP/Backend/$redirect");
            exit();
        } else {
            echo "Invalid Password!";
        }
    } else {
        echo "User not found!";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid Request!";
}
?>
