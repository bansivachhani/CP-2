<?php
require 'vendor/autoload.php';  // Correct path if it's inside Backend/vendor

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

include 'db_connection.php';

if (isset($_POST['import'])) {
    $fileName = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {
        $spreadsheet = IOFactory::load($fileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Skip header row

            $student_id = $row[0];
            $student_name = $row[1];
            $student_email = $row[2];

            $query = "INSERT INTO students (student_id, student_name, student_email)
                      VALUES ('$student_id', '$student_name', '$student_email')";
            
            mysqli_query($conn, $query);
        }

        echo "<script>alert('Student Data Imported Successfully!'); window.location.href='admin_dashboard.php';</script>";
    }
}
?>
