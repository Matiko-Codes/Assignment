<?php
session_start();

echo '<link rel="stylesheet" type="text/css" href="CSS/updateauthor.css">';

// Check if the user is logged in as an Administrator
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Administrator') {
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

// Function to delete an Author
function deleteAuthor($userId) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Delete the Author
    $query = "DELETE FROM users WHERE userId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Get userId from the URL
$userId = isset($_GET['userId']) ? $_GET['userId'] : '';

// Get Author details
$authorDetails = getAuthorDetails($userId);

// Handle form submission for updating Author details or deleting Author
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        // Update Author details
        $fullName = $_POST['full_name'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phone_number'];
        $userName = $_POST['user_name'];
        $password = $_POST['password'];
        $address = $_POST['address'];

        if (updateAuthor($userId, $fullName, $email, $phoneNumber, $userName, $password, $address)) {
            echo "Author details updated successfully!";
            // Redirect to avoid resubmission on page refresh
            header("Location: manage_authors.php");
            exit();
        } else {
            echo "Failed to update Author details.";
        }
    } elseif (isset($_POST['delete'])) {
        // Delete Author
        if (deleteAuthor($userId)) {
            echo "Author deleted successfully!";
            // Redirect to avoid resubmission on page refresh
            header("Location: manage_authors.php");
            exit();
        } else {
            echo "Failed to delete Author.";
        }
    }
}
?>

<!-- Update Author Form -->
<h2>Update Author Details</h2>
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

    <button type="submit" name="update">Update Author</button>
    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this Author?')">Delete Author</button>
</form>

<!-- Back to Manage Authors Button -->
<br><a href="manage_authors.php">Go back to Manage Authors</a>