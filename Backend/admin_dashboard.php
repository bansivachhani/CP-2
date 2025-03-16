<?php
session_start();
include("db_connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.html");
    exit();
}

// Fetch Admin's Name
$admin_id = $_SESSION['admin_id'];
$query = "SELECT name FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_name = $row['name'];
} else {
    $admin_name = "Admin";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../UI/styles.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f0f4f7;
        }
        
        .sidebar {
            background-color: #343a40;
            padding: 20px;
            width: 250px;
            color: white;
        }
        
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        
        .sidebar a:hover {
            background-color: #495057;
        }

        .main-content {
            flex-grow: 1;
            padding: 30px;
        }

        h1 {
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        
        <h4>Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h4>
        <a href="view_students.php">👨‍🎓 View Students</a>
        <a href="view_faculty.php">👩‍🏫 View Faculty</a>
        <a href="view_all_ratings.php">📊 View All Ratings</a>    
        <a href="approve_leaves.php">✅ Approve/Reject Leaves</a>
        <a href="logout.php">🔓 Logout</a>
    </div>

    <div class="main-content">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($admin_name); ?>. Use the menu to navigate through the admin functionalities.</p>
        
        <h3>Import Student/Faculty Data from Excel</h3>
        <form action="import_excel.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" accept=".xls, .xlsx" required>
            <button type="submit" name="import" class="btn btn-primary mt-2">Import Excel</button>
        </form>
    </div>
</body>
</html>
