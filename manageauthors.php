<?php
session_start();

// Check if the Administrator is not logged in, redirect to the login page
if (!isset($_SESSION['administrator'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Fetch all authors from the database
$conn = new DatabaseConnection();
$connection = $conn->getConnection();

$sql = "SELECT * FROM users WHERE UserType = 'Author'";
$result = $connection->query($sql);

// Check if any results were returned
if ($result->num_rows > 0) {
    $authors = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Handle the case where no authors are found
    $authors = [];
}

// Close the database connection
$conn->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Authors</title>
</head>
<body>
    <h2>Manage Authors</h2>

    <a href="addauthor.php">Add a new Author</a>

    <h3>List of Authors</h3>
    <table border="1">
        <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Action</th>
        </tr>
        <?php foreach ($authors as $author) : ?>
            <tr>
                <td><?php echo $author['userId']; ?></td>
                <td><?php echo $author['Full_Name']; ?></td>
                <td><?php echo $author['email']; ?></td>
                <td><?php echo $author['phone_Number']; ?></td>
                <td>
                    <a href="updateauthor.php?id=<?php echo $author['userId']; ?>">Update</a>
                    <a href="deleteauthor.php?id=<?php echo $author['userId']; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="exportauthors.php">Export Authors List</a>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>