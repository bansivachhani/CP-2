<?php
require 'vendor/autoload.php';  // Correct path if it's inside Backend/vendor

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

include 'db_connection.php';  // Including the connection

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

if (isset($_POST['import'])) {
    $fileName = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {
        try {
            $spreadsheet = IOFactory::load($fileName);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            foreach ($rows as $index => $row) {
                if ($index == 0) continue; // Skip header row

                $name = mysqli_real_escape_string($conn, $row[0]);
                $email = mysqli_real_escape_string($conn, $row[1]);
                $password = mysqli_real_escape_string($conn, $row[2]);
                $semester = trim($row[3]);  // Trim any extra spaces
                if (!is_numeric($semester) || $semester === '') {  // Check if it's a valid number
                    echo "<script>alert('Invalid semester value in the Excel file. Please check your data.');</script>";
                    continue;  // Skip this row
                }
                $semester = intval($semester);  // Convert to integer if valid


                // Ensure no empty data is being inserted
                if (empty($name) || empty($email) || empty($password) || empty($semester)) {
                    echo "<script>alert('Some rows are missing data. Please check your Excel file.');</script>";
                    continue;
                }

                $query = "INSERT INTO students (name, email, password, semester) 
                          VALUES ('$name', '$email', '$password', '$semester')";

                if (!mysqli_query($conn, $query)) {
                    echo "<script>alert('Error inserting row: " . mysqli_error($conn) . "');</script>";
                }
            }

            echo "<script>alert('Student Data Imported Successfully!'); window.location.href='admin_dashboard.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error reading Excel file: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('No file selected or file is empty.'); window.location.href='admin_dashboard.php';</script>";
    }
}
?>
