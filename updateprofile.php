<?php
session_start();

// Check if the Super_User is not logged in, redirect to the login page
if (!isset($_SESSION['super_user'])) {
    header('Location: index.php');
    exit();
}

// Include necessary files
require_once('connection.php');

// Fetch Super_User details from the database (you may use the logged-in Super_User's ID)
$superUserId = 1;
$conn = new DatabaseConnection();
$connection = $conn->getConnection();

$sql = "SELECT userId, Full_Name, email, phone_Number, Password, UserType, AccessTime, profile_Image, Address FROM users WHERE userId = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $superUserId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$result) {
    die("Query execution failed: " . $stmt->error);
}

// Check if any results were returned
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Output additional information for debugging
    echo "User details not found. UserId: $superUserId";
    exit();
}

if ($stmt === false) {
    // Handle statement preparation error
    die("Statement preparation failed: " . $connection->error);
}

// Bind the results directly to variables
$stmt->bind_result($userId, $full_name, $email, $phone_Number, $password, $userType, $accessTime, $profileImage, $address);

// Fetch the first (and only) result row
if ($stmt->fetch()) {
    $user = [
        'userId' => $userId,
        'Full_Name' => $full_name,
        'email' => $email,
        'phone_Number' => $phone_Number,
        'Password' => $password,
        'UserType' => $userType,
        'AccessTime' => $accessTime,
        'profile_Image' => $profileImage,
        'Address' => $address,
    ];
} else {
    // Handle the case where user details are not found
    echo "User details not found.";
    exit();
}


// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFullName = $_POST['full_name'];
    $newEmail = $_POST['email'];
    $newPhoneNumber = $_POST['phone_number'];
    $newPassword = $_POST['password'];

// Update Super_User details in the database
$updateSql = "UPDATE users SET Full_Name=?, email=?, phone_Number=?, Password=? WHERE userId=?";
$updateStmt = $connection->prepare($updateSql);

// Make sure the data types and the number of parameters match the placeholders
$updateStmt->bind_param('ssssi', $newFullName, $newEmail, $newPhoneNumber, $newPassword, $superUserId);
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
        <input type="text" name="full_name" value="<?php echo $user['Full_Name']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" value="<?php echo $user['phone_Number']; ?>"><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Update Profile">
    </form>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>