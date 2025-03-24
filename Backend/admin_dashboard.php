<?php
ob_start(); // Prevent header issues
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("db_connection.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.html");
    exit();
}

// Fetch Admin's Name
$admin_id = $_SESSION['admin_id'];
$admin_name = "Admin";

$query = "SELECT name FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_name = $row['name'];
}
$stmt->close();

// Fetch Student Ratings
$ratings_sql = "SELECT students.name AS student_name, faculty.name AS faculty_name, ratings.value 
                FROM ratings
                JOIN students ON ratings.student_id = students.student_id
                JOIN faculty ON ratings.faculty_id = faculty.faculty_id";
$ratings_result = $conn->query($ratings_sql);

// Fetch Pending Leave Requests
$leaves_sql = "SELECT leave_requests.leave_id, faculty.name AS faculty_name, leave_requests.reason, leave_requests.status
               FROM leave_requests
               JOIN faculty ON leave_requests.faculty_id = faculty.faculty_id
               WHERE leave_requests.status = 'Pending'";
$leaves_result = $conn->query($leaves_sql);
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

        <!-- Student Ratings -->
        <h3>Student Ratings:</h3>
        <?php if ($ratings_result && $ratings_result->num_rows > 0) { ?>
            <table class="table table-bordered">
                <tr><th>Student</th><th>Faculty</th><th>Rating</th></tr>
                <?php while ($row = $ratings_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['faculty_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['value']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { echo "<p>No ratings found.</p>"; } ?>

        <!-- Leave Requests -->
        <h3>Leave Requests:</h3>
        <?php if ($leaves_result && $leaves_result->num_rows > 0) { ?>
            <form method="POST" action="approve_leave.php">
                <table class="table table-bordered">
                    <tr><th>Faculty</th><th>Reason</th><th>Status</th><th>Action</th></tr>
                    <?php while ($row = $leaves_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['faculty_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <button type="submit" name="approve" value="<?php echo $row['leave_id']; ?>" class="btn btn-success btn-sm">Approve</button>
                                <button type="submit" name="reject" value="<?php echo $row['leave_id']; ?>" class="btn btn-danger btn-sm">Reject</button>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </form>
        <?php } else { echo "<p>No leave requests.</p>"; } ?>

        <!-- Excel Import -->
        <h3>Import Student/Faculty Data from Excel</h3>
        <form action="import_excel.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" accept=".xls, .xlsx" required>
            <button type="submit" name="import" class="btn btn-primary mt-2">Import Excel</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
