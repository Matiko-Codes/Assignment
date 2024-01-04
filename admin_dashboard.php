<?php
session_start();

// Check if the user is logged in as an Administrator
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Administrator') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard</title>
</head>
<body>

<h1>Administrator Dashboard</h1>

<!-- Buttons for Administrator -->
<ul>
    <li><a href="admin_update_profile.php">Update Profile</a></li>
    <li><a href="manage_authors.php">Manage Authors</a></li>
    <li><a href="view_articles.php">View Articles</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>