<?php
session_start();

// Check if the user is logged in as an Author
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Author') {
    header('Location: index.php');
    exit();
}

require_once('connection.php');

// Function to get Author details by userId
function getAuthorDetails($userId) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Get Author details
    $query = "SELECT * FROM users WHERE userId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $authorDetails = $result->fetch_assoc();

    $stmt->close();
    $dbConnection->closeConnection();

    return $authorDetails;
}

// Function to update an Author's details
function updateAuthor($userId, $fullName, $email, $phoneNumber, $userName, $password, $address) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the Author's details
    $query = "UPDATE users SET Full_Name = ?, email = ?, phone_Number = ?, User_Name = ?, Password = ?, Address = ? WHERE userId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $fullName, $email, $phoneNumber, $userName, $hashedPassword, $address, $userId);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Get userId from the session
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Get Author details
$authorDetails = getAuthorDetails($userId);

// Handle form submission for updating Author details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $userName = $_POST['user_name'];
    $password = $_POST['password'];
    $address = $_POST['address'];

    // Update the Author's details
    if (updateAuthor($userId, $fullName, $email, $phoneNumber, $userName, $password, $address)) {
        echo "Author details updated successfully!";
        // Redirect to avoid resubmission on page refresh
        header("Location: author_dashboard.php");
        exit();
    } else {
        echo "Failed to update Author details.";
    }
}
?>

<!-- Update Author Profile Form -->
<h2>Update Author Profile</h2>
<form method="post">
    <label for="full_name">Full Name:</label>
    <input type="text" name="full_name" value="<?php echo isset($authorDetails['Full_Name']) ? $authorDetails['Full_Name'] : ''; ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo isset($authorDetails['email']) ? $authorDetails['email'] : ''; ?>" required>

    <label for="phone_number">Phone Number:</label>
    <input type="text" name="phone_number" value="<?php echo isset($authorDetails['phone_Number']) ? $authorDetails['phone_Number'] : ''; ?>">

    <label for="user_name">Username:</label>
    <input type="text" name="user_name" value="<?php echo isset($authorDetails['User_Name']) ? $authorDetails['User_Name'] : ''; ?>" required>

    <label for="password">New Password:</label>
    <input type="password" name="password">

    <label for="address">Address:</label>
    <textarea name="address"><?php echo isset($authorDetails['Address']) ? $authorDetails['Address'] : ''; ?></textarea>

    <button type="submit">Update Profile</button>
</form>

<!-- Back to Author Dashboard Button -->
<br><a href="author_dashboard.php">Go back to Author Dashboard</a>