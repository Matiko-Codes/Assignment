<?php
session_start();

// Check if the user is logged in as an Author
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Author') {
    header('Location: index.php');
    exit();
}

require_once('connection.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/authordashboard.css">
    <title>Author Dashboard</title>
</head>
<body>

<h1>Welcome, <?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Author'; ?>!</h1>

<!-- Author Dashboard Links -->
<ul>
    <li><a href="author_update_profile.php">Update Profile</a></li>
    <li><a href="manage_articles.php">Manage Articles</a></li>
    <li><a href="view_articles.php">View Articles</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>