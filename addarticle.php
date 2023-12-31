<?php
session_start();

// Check if the Author is not logged in, redirect to the login page
if (!isset($_SESSION['author'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Handle form submission for adding an article
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleTitle = $_POST['article_title'];
    $articleText = $_POST['article_text'];

    // Insert Article details into the database
    $conn = new DatabaseConnection();
    $connection = $conn->getConnection();

    $authorId = $_SESSION['author']['userId'];

    $insertSql = "INSERT INTO articles (authorId, article_title, article_full_text) VALUES (?, ?, ?)";
    $insertStmt = $connection->prepare($insertSql);
    $insertStmt->bind_param('iss', $authorId, $articleTitle, $articleText);
    $insertStmt->execute();

    // Redirect to the manage articles page after adding
    header('Location: managearticles.php');
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
    <title>Add Article</title>
</head>
<body>
    <h2>Add Article</h2>

    <form action="addarticle.php" method="post">
        <label for="article_title">Article Title:</label>
        <input type="text" name="article_title" required><br>

        <label for="article_text">Article Text:</label>
        <textarea name="article_text" rows="5" required></textarea><br>

        <input type="submit" value="Add Article">
    </form>

    <a href="managearticles.php">Back to Manage Articles</a>
</body>
</html>