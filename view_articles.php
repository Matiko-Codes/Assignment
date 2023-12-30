<?php
session_start();

// Check if the Super_User is not logged in, redirect to the login page
if (!isset($_SESSION['super_user'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Fetch the last 6 articles in descending order by article_created_date
$conn = new DatabaseConnection();
$connection = $conn->getConnection();

$sql = "SELECT * FROM articles ORDER BY article_created_date DESC LIMIT 6";
$stmt = $connection->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$articles = $result->fetch_all(MYSQLI_ASSOC);

$conn->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Articles</title>
</head>
<body>
    <h2>View Articles</h2>

    <?php if (empty($articles)): ?>
        <p>No articles found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <strong><?php echo $article['article_title']; ?></strong><br>
                    <?php echo $article['article_full_text']; ?><br>
                    Created Date: <?php echo $article['article_created_date']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>