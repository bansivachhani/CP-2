<?php
session_start();
include("db_connection.php");

$query = "SELECT * FROM students";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Students List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
