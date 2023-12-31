<?php
session_start();

// Check if the Author is not logged in, redirect to the login page
if (!isset($_SESSION['author'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Fetch Article details from the database (you may use the Article's ID from the URL)
$articleIdToUpdate = $_GET['id']; // Assuming you pass the article ID in the URL
$sql = "SELECT * FROM articles WHERE articleId = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $articleIdToUpdate);
$stmt->execute();
$result = $stmt->get_result();

// Check if any results were returned
if ($result->num_rows > 0) {
    $articleToUpdate = $result->fetch_assoc();
} else {
    // Handle the case where article details are not found
    echo "Article details not found.";
    exit();
}

// Handle form submission for updating article details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedArticleTitle = $_POST['article_title'];
    $updatedArticleFullText = $_POST['article_full_text'];

    // Update Article details in the database
    $updateSql = "UPDATE articles SET article_title=?, article_full_text=? WHERE articleId=?";
    $updateStmt = $connection->prepare($updateSql);
    $updateStmt->bind_param('ssi', $updatedArticleTitle, $updatedArticleFullText, $articleIdToUpdate);
    $updateStmt->execute();

    // Redirect to the Manage Articles page after updating
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
    <title>Update Article</title>
</head>
<body>
    <h2>Update Article</h2>

    <form action="updatearticle.php?id=<?php echo $articleIdToUpdate; ?>" method="post">
        <label for="article_title">Title:</label>
        <input type="text" name="article_title" value="<?php echo $articleToUpdate['article_title']; ?>" required><br>

        <label for="article_full_text">Full Text:</label>
        <textarea name="article_full_text" required><?php echo $articleToUpdate['article_full_text']; ?></textarea><br>

        <input type="submit" value="Update Article">
    </form>

    <a href="managearticles.php">Back to Manage Articles</a>
</body>
</html>