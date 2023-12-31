<?php
session_start();

// Check if the Administrator is not logged in, redirect to the login page
if (!isset($_SESSION['administrator'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Handle form submission for adding an author
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authorFullName = $_POST['full_name'];
    $authorEmail = $_POST['email'];
    $authorPhoneNumber = $_POST['phone_number'];
    $authorPassword = $_POST['password'];

    // Insert Author details into the database
    $conn = new DatabaseConnection();
    $connection = $conn->getConnection();

    $insertSql = "INSERT INTO users (Full_Name, email, phone_Number, Password, UserType) VALUES (?, ?, ?, ?, 'Author')";
    $insertStmt = $connection->prepare($insertSql);
    $insertStmt->bind_param('ssss', $authorFullName, $authorEmail, $authorPhoneNumber, $authorPassword);
    $insertStmt->execute();

    // Redirect to the manage authors page after adding
    header('Location: manageauthors.php');
    exit();
}

// Close the database connection
$conn->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Author</title>
</head>
<body>
    <h2>Add Author</h2>

    <form action="addauthor.php" method="post">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number"><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Add Author">
    </form>

    <a href="manageauthors.php">Back to Manage Authors</a>
</body>
</html>