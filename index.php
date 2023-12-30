<?php
session_start();

// Include necessary files
require_once('connection.php');

// Create an instance of DatabaseConnection
$conn = new DatabaseConnection();
$connection = $conn->getConnection();

// Check if the Super_User is already logged in, redirect to dashboard if true
if (isset($_SESSION['super_user'])) {
    header('Location: dashboard.php');
    exit();
}

$sql = "SELECT * FROM users WHERE User_Name = ? AND Password = ? AND (UserType = 'Administrator' OR IsAdministrator = 1)";
$stmt = $connection->prepare($sql);
$stmt->bind_param('ss', $username, $password);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>