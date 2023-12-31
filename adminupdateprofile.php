<?php
session_start();

// Check if the Administrator is not logged in, redirect to the login page
if (!isset($_SESSION['administrator'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Fetch Administrator details from the database (you may use the logged-in Administrator's ID)
$administratorId = $_SESSION['administrator']['userId'];
$conn = new DatabaseConnection();
$connection = $conn->getConnection();

$sql = "SELECT * FROM users WHERE userId = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $administratorId);
$stmt->execute();
$result = $stmt->get_result();

// Check if any results were returned
if ($result->num_rows > 0) {
    $administrator = $result->fetch_assoc();
} else {
    // Handle the case where administrator details are not found
    echo "Administrator details not found.";
    exit();
}

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFullName = $_POST['full_name'];
    $newEmail = $_POST['email'];
    $newPhoneNumber = $_POST['phone_number'];
    $newPassword = $_POST['password'];

    // Update Administrator details in the database
    $updateSql = "UPDATE users SET Full_Name=?, email=?, phone_Number=?, Password=? WHERE userId=?";
    $updateStmt = $connection->prepare($updateSql);
    $updateStmt->bind_param('ssssi', $newFullName, $newEmail, $newPhoneNumber, $newPassword, $administratorId);
    $updateStmt->execute();

    // Redirect to the dashboard after updating
    header('Location: dashboard.php');
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
    <title>Update Profile</title>
</head>
<body>
    <h2>Update Profile</h2>

    <form action="updateprofile.php" method="post">
        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" value="<?php echo $administrator['Full_Name']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $administrator['email']; ?>" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" value="<?php echo $administrator['phone_Number']; ?>"><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Update Profile">
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>