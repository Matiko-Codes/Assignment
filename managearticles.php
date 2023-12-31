<?php
session_start();

// Check if the Author is not logged in, redirect to the login page
if (!isset($_SESSION['author'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Fetch Author details from the database (you may use the logged-in Author's ID)
$authorId = $_SESSION['author']['userId'];
$conn = new DatabaseConnection();
$connection = $conn->getConnection();

$sql = "SELECT * FROM users WHERE userId = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $authorId);
$stmt->execute();
$result = $stmt->get_result();

// Check if any results were returned
if ($result->num_rows > 0) {
    $author = $result->fetch_assoc();
} else {
    // Handle the case where author details are not found
    echo "Author details not found.";
    exit();
}

// Handle form submission for managing articles
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add code for managing articles (add, update, delete)
}

// Fetch articles authored by the Author
$sql = "SELECT * FROM articles WHERE authorId = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $authorId);
$stmt->execute();
$result = $stmt->get_result();

// Check if any results were returned
if ($result->num_rows > 0) {
    $articles = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Handle the case where no articles are found
    $articles = [];
}

// Close the database connection
$conn->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles</title>
</head>
<body>
    <h2>Manage Articles</h2>

    <a href="addarticle.php">Add a new Article</a>

    <h3>List of Articles</h3>
    <table border="1">
        <tr>
            <th>Article ID</th>
            <th>Title</th>
            <th>Full Text</th>
            <th>Created Date</th>
            <th>Last Update</th>
            <th>Display</th>
            <th>Order</th>
            <th>Action</th>
        </tr>
        <?php foreach ($articles as $article) : ?>
            <tr>
                <td><?php echo $article['articleId']; ?></td>
                <td><?php echo $article['article_title']; ?></td>
                <td><?php echo $article['article_full_text']; ?></td>
                <td><?php echo $article['article_created_date']; ?></td>
                <td><?php echo $article['article_last_update']; ?></td>
                <td><?php echo $article['article_display']; ?></td>
                <td><?php echo $article['article_order']; ?></td>
                <td>
                    <a href="updatearticle.php?id=<?php echo $article['articleId']; ?>">Update</a>
                    <a href="deletearticle.php?id=<?php echo $article['articleId']; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>