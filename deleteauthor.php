<?php
session_start();

// Check if the Administrator is not logged in, redirect to the login page
if (!isset($_SESSION['administrator'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Fetch Author details from the database based on the author ID
if (isset($_GET['id'])) {
    $authorId = $_GET['id'];
    
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

    // Close the database connection
    $conn->closeConnection();
} else {
    // Redirect to the manage authors page if author ID is not provided
    header('Location: manageauthors.php');
    exit();
}

// Handle form submission for deleting an author
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete Author from the database
    $conn = new DatabaseConnection();
    $connection = $conn->getConnection();

    $deleteSql = "DELETE FROM users WHERE userId=?";
    $deleteStmt = $connection->prepare($deleteSql);
    $deleteStmt->bind_param('i', $authorId);
    $deleteStmt->execute();

    // Redirect to the manage authors page after deleting
    header('Location: manageauthors.php');
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
    <title>Delete Author</title>
</head>
<body>
    <h2>Delete Author</h2>

    <p>Are you sure you want to delete the author: <?php echo $author['Full_Name']; ?>?</p>

    <form action="deleteauthor.php?id=<?php echo $authorId; ?>" method="post">
        <input type="submit" value="Delete Author">
    </form>

    <a href="manageauthors.php">Cancel</a>
</body>
</html>