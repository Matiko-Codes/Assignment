<?php
session_start();

// Check if the Super_User is not logged in, redirect to the login page
if (!isset($_SESSION['super_user'])) {
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super_User Dashboard</title>
</head>
<body>
    <h2>Welcome, Super Admin!</h2>

    <ul>
        <li><a href="updateprofile.php">Update Profile</a></li>
        <li><a href="manageuser.php">Manage Other Users</a></li>
        <li><a href="view_articles.php">View Articles</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>