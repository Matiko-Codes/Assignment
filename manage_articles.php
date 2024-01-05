<?php
session_start();

// Check if the user is logged in as an Author
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Author') {
    header('Location: index.php');
    exit();
}

require_once('connection.php');

// Function to add a new Article
function addArticle($title, $content) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Get Author's ID from the session
    $authorId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

    // Insert a new Article
    $query = "INSERT INTO articles (authorId, article_title, article_full_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $authorId, $title, $content);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Function to get all Articles by Author
function getAllArticlesByAuthor($authorId) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Get all Articles by Author
    $query = "SELECT * FROM articles WHERE authorId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $authorId);
    $stmt->execute();
    $result = $stmt->get_result();

    $articles = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
    }

    $stmt->close();
    $dbConnection->closeConnection();

    return $articles;
}

// Function to update an Article's details
function updateArticle($articleId, $title, $content) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Update the Article's details
    $query = "UPDATE articles SET article_title = ?, article_full_text = ? WHERE articleId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $title, $content, $articleId);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Function to delete an Article
function deleteArticle($articleId) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Delete the Article
    $query = "DELETE FROM articles WHERE articleId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $articleId);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Get Author's ID from the session
$authorId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Handle form submission for adding a new Article
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_article'])) {
    $title = $_POST['article_title'];
    $content = $_POST['article_content'];

    // Add the new Article
    if (addArticle($title, $content)) {
        echo "Article added successfully!";
    } else {
        echo "Failed to add Article.";
    }
}

// Get all Articles by Author
$allArticles = getAllArticlesByAuthor($authorId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/managearticles.css">
    <title>Manage Articles</title>
</head>
<body>

<h1>Manage Articles</h1>

<!-- Add Article Form -->
<h2>Add New Article</h2>
<form method="post">
    <label for="article_title">Title:</label>
    <input type="text" name="article_title" required>

    <label for="article_content">Content:</label>
    <textarea name="article_content" required></textarea>

    <button type="submit" name="add_article">Add Article</button>
</form>

<!-- List of Articles -->
<h2>List of Articles</h2>
<?php foreach ($allArticles as $article) : ?>
    <div>
        <h3><?php echo $article['article_title']; ?></h3>
        <p><?php echo $article['article_full_text']; ?></p>
        <form method="post">
            <input type="hidden" name="article_id" value="<?php echo $article['articleId']; ?>">
            <button type="submit" name="edit_article">Edit</button>
            <button type="submit" name="delete_article" onclick="return confirm('Are you sure you want to delete this Article?')">Delete</button>
        </form>
    </div>
    <hr>
<?php endforeach; ?>

<!-- Button to return to author_dashboard.php -->
<a href="author_dashboard.php">Back to Author Dashboard</a>

</body>
</html>