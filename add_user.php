<?php
session_start();

// Check if the Super_User is not logged in, redirect to the login page
if (!isset($_SESSION['super_user'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Handle adding a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $fullName = $_POST['full_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone_number'];
        $userName = $_POST['username'];
        $password = $_POST['password'];
        $userType = isset($_POST['is_admin']) ? 'Administrator' : 'Regular User'; // Check if the user is an administrator
        $address = $_POST['address'];

        $conn = new DatabaseConnection();
        $connection = $conn->getConnection();

        $insertSql = "INSERT INTO users (Full_Name, email, phone_Number, User_Name, Password, UserType, Address, IsAdministrator) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $connection->prepare($insertSql);
        $isAdministrator = $userType === 'Administrator' ? 1 : 0; // Set IsAdministrator based on user type
        $insertStmt->bind_param('sssssssi', $fullName, $email, $phone, $userName, $password, $userType, $address, $isAdministrator);
        $insertStmt->execute();

        // Redirect to the Manage Other Users page after adding the user
        header('Location: manageuser.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <h2>Add User</h2>

    <form action="add_user.php" method="post">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number"><br>

        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="user_type">User Type:</label>
        <input type="text" name="user_type"><br>

        <label for="address">Address:</label>
        <textarea name="address"></textarea><br>

        <input type="submit" name="add_user" value="Add User">
    </form>
    
    <!-- Checkbox to designate the user as an administrator -->
    <label for="is_admin">Administrator:</label>
        <input type="checkbox" name="is_admin">

    <a href="manageuser.php">Back to Manage Other Users</a>
</body>
</html>