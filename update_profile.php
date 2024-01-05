<?php
session_start();

// Check if the user is logged in as a Super User
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'SuperUser') {
    header('Location: index.php');
    exit();
}

require_once('connection.php');

// Function to update the user's profile
function updateProfile($userId, $fullName, $email, $phoneNumber, $password, $profileImage, $address) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's profile
    $query = "UPDATE users SET Full_Name = ?, email = ?, phone_Number = ?, Password = ?, profile_Image = ?, Address = ? WHERE userId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $fullName, $email, $phoneNumber, $hashedPassword, $profileImage, $address, $userId);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $password = $_POST['password'];
    $profileImage = $_POST['profile_image'];
    $address = $_POST['address'];

    // Update the profile
    if (updateProfile($userId, $fullName, $email, $phoneNumber, $password, $profileImage, $address)) {
        echo "Profile updated successfully!";
        echo '<br><a href="super_user_dashboard.php">Go back to Dashboard</a>';
        exit();
    } else {
        echo "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="CSS/updateprofile.css">
    <title>Update Profile</title>
</head>
<body>

<h1>Update Profile</h1>

<form method="post">
    <label for="full_name">Full Name:</label>
    <input type="text" name="full_name" value="<?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''; ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>

    <label for="phone_number">Phone Number:</label>
    <input type="text" name="phone_number" value="<?php echo isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : ''; ?>">

    <label for="password">New Password:</label>
    <input type="password" name="password" required>

    <label for="profile_image">Profile Image URL:</label>
    <input type="text" name="profile_image" value="<?php echo isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : ''; ?>">

    <label for="address">Address:</label>
    <textarea name="address"><?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?></textarea>

    <button type="submit">Update Profile</button>
</form>

</body>
</html>