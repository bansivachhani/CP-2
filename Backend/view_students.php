<?php
include("db_connection.php");

$query = "SELECT student_id, name, email FROM students"; // ✅ Corrected Column Name
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['student_id'] . "</td>"; // ✅ Corrected Key
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No students found.";
}

$conn->close();
?>
