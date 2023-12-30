<?php
session_start();

// Check if the Super_User is not logged in, redirect to the login page
if (!isset($_SESSION['super_user'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Fetch user details for pre-filling the update form
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
    <title>Update User</title>
</head>
<body>
    <h2>Update User</h2>

    <form action="update_user.php" method="post">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" value="<?php echo isset($user['Full_Name']) ? $user['Full_Name'] : ''; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" value="<?php echo isset($user['phone_Number']) ? $user['phone_Number'] : ''; ?>"><br>

        <label for="user_type">User Type:</label>
        <input type="text" name="user_type" value="<?php echo isset($user['UserType']) ? $user['UserType'] : ''; ?>"><br>

        <label for="address">Address:</label>
        <textarea name="address"><?php echo isset($user['Address']) ? $user['Address'] : ''; ?></textarea><br>

        <!-- Hidden field to store the user ID -->
        <input type="hidden" name="user_id" value="<?php echo isset($user['userId']) ? $user['userId'] : ''; ?>">

        <input type="submit" name="update_user" value="Update User">
    </form>

    <a href="manageuser.php">Back to Manage Other Users</a>
</body>
</html>