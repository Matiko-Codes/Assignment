<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'SuperUser') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super User Dashboard</title>
</head>
<body>

<h1>Super User Dashboard</h1>

<!-- Update Profile Button -->
<a href="update_profile.php">Update Profile</a>

<!-- Manage Other Users Button -->
<a href="manage_other_users.php">Manage Other Users</a>

<!-- View Articles Button -->
<a href="view_articles.php">View Articles</a>

<!-- Logout Button -->
<a href="logout.php">Logout</a>

</body>
</html>