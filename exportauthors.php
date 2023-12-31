<?php
session_start();

// Check if the Administrator is not logged in, redirect to the login page
if (!isset($_SESSION['administrator'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');
require_once 'PHPExcel/Classes/PHPExcel.php';

// Create a new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Your Name")
                             ->setLastModifiedBy("Your Name")
                             ->setTitle("Authors Export")
                             ->setSubject("Authors Data")
                             ->setDescription("Exported Authors Data")
                             ->setKeywords("authors export");

// Add data to the Excel sheet
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Full Name');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Email');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Phone Number');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'User Type');

// Fetch Authors from the database
$conn = new DatabaseConnection();
$connection = $conn->getConnection();

$sql = "SELECT * FROM users WHERE UserType = 'Author'";
$result = $connection->query($sql);

$row = 2; // Start from row 2

if ($result->num_rows > 0) {
    while ($author = $result->fetch_assoc()) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $author['Full_Name']);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $author['email']);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $author['phone_Number']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $author['UserType']);
        $row++;
    }
}

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="authors_export.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit();
?>