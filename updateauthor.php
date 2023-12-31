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

// Handle form submission for updating an author
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFullName = $_POST['full_name'];
    $newEmail = $_POST['email'];
    $newPhoneNumber = $_POST['phone_number'];
    $newPassword = $_POST['password'];

    // Update Author details in the database
    $conn = new DatabaseConnection();
    $connection = $conn->getConnection();

    $updateSql = "UPDATE users SET Full_Name=?, email=?, phone_Number=?, Password=? WHERE userId=?";
    $updateStmt = $connection->prepare($updateSql);
    $updateStmt->bind_param('ssssi', $newFullName, $newEmail, $newPhoneNumber, $newPassword, $authorId);
    $updateStmt->execute();

    // Redirect to the manage authors page after updating
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
    <title>Update Author</title>
</head>
<body>
    <h2>Update Author</h2>

    <form action="updateauthor.php?id=<?php echo $authorId; ?>" method="post">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" value="<?php echo $author['Full_Name']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $author['email']; ?>" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" value="<?php echo $author['phone_Number']; ?>"><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Update Author">
    </form>

    <a href="manageauthors.php">Back to Manage Authors</a>
</body>
</html>