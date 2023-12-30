<?php
session_start();

// Check if the Super_User is not logged in, redirect to the login page
if (!isset($_SESSION['super_user'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Fetch user details for displaying in the confirmation message
if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    $conn = new DatabaseConnection();
    $connection = $conn->getConnection();

    $selectSql = "SELECT * FROM users WHERE userId = ?";
    $selectStmt = $connection->prepare($selectSql);
    $selectStmt->bind_param('i', $userId);
    $selectStmt->execute();
    $result = $selectStmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user details were successfully fetched
    if (!$user) {
        // Handle the case where user details are not found
        echo "User details not found.";
        exit();
    }

    $conn->closeConnection();
} else {
    // Handle the case where no user ID is provided in the URL
    echo "User ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
</head>
<body>
    <h2>Delete User</h2>

    <p>Are you sure you want to delete the user: <?php echo isset($user['Full_Name']) ? $user['Full_Name'] : ''; ?>?</p>

    <form action="delete_user.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo isset($user['userId']) ? $user['userId'] : ''; ?>">
        <input type="submit" name="delete_user" value="Yes, Delete User">
    </form>

    <a href="manageuser.php">Cancel</a>
</body>
</html>