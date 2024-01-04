<?php
session_start();

// Check if the user is logged in as a Super User
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'SuperUser') {
    header('Location: index.php');
    exit();
}

require_once('connection.php');

// Function to add a new user (Administrator)
function addUser($fullName, $email, $phoneNumber, $userName, $password, $address) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Add a new user (Administrator)
    $query = "INSERT INTO users (Full_Name, email, phone_Number, User_Name, Password, UserType, Address) VALUES (?, ?, ?, ?, ?, 'Administrator', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $fullName, $email, $phoneNumber, $userName, $hashedPassword, $address);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Function to get a list of all users
function getAllUsers() {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Get all users (excluding the current Super User)
    $query = "SELECT userId, Full_Name, email, phone_Number, User_Name, UserType, AccessTime, profile_Image, Address FROM users WHERE UserType != 'SuperUser'";
    $result = $conn->query($query);

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    $dbConnection->closeConnection();

    return $users;
}

// Function to update user details
function updateUser($userId, $fullName, $email, $phoneNumber, $userName, $password, $address) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update user details
    $query = "UPDATE users SET Full_Name = ?, email = ?, phone_Number = ?, User_Name = ?, Password = ?, Address = ? WHERE userId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $fullName, $email, $phoneNumber, $userName, $address, $userId);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Function to delete user
function deleteUser($userId) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Delete user
    $query = "DELETE FROM users WHERE userId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        switch ($action) {
            case 'add':
                // Handle add user form submission
                $fullName = $_POST['full_name'];
                $email = $_POST['email'];
                $phoneNumber = $_POST['phone_number'];
                $userName = $_POST['user_name'];
                $password = $_POST['password'];
                $address = $_POST['address'];

                if (addUser($fullName, $email, $phoneNumber, $userName, $password, $address)) {
                    echo "User added successfully!";
                } else {
                    echo "Failed to add user.";
                }
                break;

            case 'update':
                // Handle update user form submission
                $userId = $_POST['user_id'];
                $fullName = $_POST['full_name'];
                $email = $_POST['email'];
                $phoneNumber = $_POST['phone_number'];
                $userName = $_POST['user_name'];
                $password = $_POST['password'];
                $address = $_POST['address'];

                if (updateUser($userId, $fullName, $email, $phoneNumber, $userName, $password, $address)) {
                    echo "User updated successfully!";
                } else {
                    echo "Failed to update user.";
                }
                break;

            case 'delete':
                // Handle delete user form submission
                $userId = $_POST['user_id'];

                if (deleteUser($userId)) {
                    echo "User deleted successfully!";
                } else {
                    echo "Failed to delete user.";
                }
                break;

            default:
                echo "Invalid action.";
                break;
        }
    }
}

// Get a list of all users
$allUsers = getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Other Users</title>
</head>
<body>

<h1>Manage Other Users</h1>

<!-- Add User Form -->
<h2>Add New User</h2>
<form method="post">
    <input type="hidden" name="action" value="add">
    <label for="full_name">Full Name:</label>
    <input type="text" name="full_name" required>
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <label for="phone_number">Phone Number:</label>
    <input type="text" name="phone_number">
    <label for="user_name">Username:</label>
    <input type="text" name="user_name" required>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <label for="address">Address:</label>
    <textarea name="address"></textarea>
    <button type="submit">Add User</button>
</form>

<!-- List of Users -->
<h2>List of Users</h2>
<table border="1">
    <tr>
        <th>User ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Username</th>
        <th>User Type</th>
        <th>Access Time</th>
        <th>Profile Image</th>
        <th>Address</th>
        <th>Action</th>
    </tr>
    <?php foreach ($allUsers as $user) : ?>
        <tr>
            <td><?php echo $user['userId']; ?></td>
            <td><?php echo $user['Full_Name']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['phone_Number']; ?></td>
            <td><?php echo $user['User_Name']; ?></td>
            <td><?php echo $user['UserType']; ?></td>
            <td><?php echo $user['AccessTime']; ?></td>
            <td><?php echo $user['profile_Image']; ?></td>
            <td><?php echo $user['Address']; ?></td>
            <td>
                <form method="post" style="display: inline;">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="user_id" value="<?php echo $user['userId']; ?>">
                    <input type="hidden" name="full_name" value="<?php echo $user['Full_Name']; ?>">
                    <input type="hidden" name="email" value="<?php echo $user['email']; ?>">
                    <input type="hidden" name="phone_number" value="<?php echo $user['phone_Number']; ?>">
                    <input type="hidden" name="user_name" value="<?php echo $user['User_Name']; ?>">
                    <input type="hidden" name="password" value="">
                    <input type="hidden" name="address" value="<?php echo $user['Address']; ?>">
                    <button type="submit">Update</button>
                </form>

                <form method="post" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_id" value="<?php echo $user['userId']; ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Export Users -->
<h2>Export Users</h2>
<form method="post">
    <input type="hidden" name="action" value="export">
    <button type="submit">Export to PDF</button>
    <button type="submit">Export to Text File</button>
    <button type="submit">Export to Excel</button>
</form>

<!-- Back to Dashboard Button -->
<br><a href="super_user_dashboard.php">Go back to Dashboard</a>

</body>
</html>