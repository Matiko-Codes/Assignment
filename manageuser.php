<?php
session_start();

// Check if the Super_User is not logged in, redirect to the login page
if (!isset($_SESSION['super_user'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Handle actions (add, update, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        // Handle adding a new user (you can implement a form for this)
        header('Location: add_user.php');
        exit();

    } elseif (isset($_POST['update_user'])) {
        // Handle updating a user (you can implement a form for this)
        header('Location: update_user.php');
        exit();

    } elseif (isset($_POST['delete_user'])) {
        // Handle deleting a user
        header('Location: delete_user.php');
        exit();

    } elseif (isset($_POST['export_users'])) {
        // Handle exporting users to Pdf, textfile, and Excel
        // ...

        // For now, redirect back to the Manage Other Users page
        header('Location: manage_users.php');
        exit();
    }
}

// Fetch all users from the database
$conn = new DatabaseConnection();
$connection = $conn->getConnection();

$sql = "SELECT * FROM users WHERE userId != ?"; // Exclude the current Super_User
$stmt = $connection->prepare($sql);
$superUserId = 1; // Replace with the actual Super_User ID
$stmt->bind_param('i', $superUserId);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$conn->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Other Users</title>
</head>
<body>
    <h2>Manage Other Users</h2>

    <!-- Navigation Links -->
    <ul>
        <li><a href="add_user.php">Add User</a></li>
        <li><a href="update_user.php">Update User</a></li>
        <li><a href="delete_user.php">Delete User</a></li>
        <li><a href="export_users.php">Export Users</a></li>
    </ul>

    <!-- Users List -->
    <h3>Users List</h3>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?php echo $user['Full_Name']; ?> |
                <a href="update_user.php?userId=<?php echo $user['userId']; ?>">Update</a> |
                <a href="delete_user.php?userId=<?php echo $user['userId']; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>