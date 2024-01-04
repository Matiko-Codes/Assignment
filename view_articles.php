<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit();
}

require_once('connection.php');

// Function to get the last 6 articles
function getLast6Articles() {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Get the last 6 articles in descending order by article_created_date
    $query = "SELECT articleId, article_title, article_full_text, article_created_date FROM articles ORDER BY article_created_date DESC LIMIT 6";
    $result = $conn->query($query);

    $articles = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
    }

    $dbConnection->closeConnection();

    return $articles;
}

// Get the last 6 articles
$last6Articles = getLast6Articles();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Articles</title>
</head>
<body>

<h1>View Articles</h1>

<?php foreach ($last6Articles as $article) : ?>
    <div>
        <h2><?php echo $article['article_title']; ?></h2>
        <p><?php echo $article['article_full_text']; ?></p>
        <p>Created Date: <?php echo $article['article_created_date']; ?></p>
    </div>
    <hr>
<?php endforeach; ?>

<!-- Back to Dashboard Button -->
<br><a href="super_user_dashboard.php">Go back to Dashboard</a>

</body>
</html>