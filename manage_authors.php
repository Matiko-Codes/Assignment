<?php
session_start();

// Check if the user is logged in as an Administrator
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Administrator') {
    header('Location: index.php');
    exit();
}

require_once('connection.php');

// Function to add a new Author
function addAuthor($fullName, $email, $phoneNumber, $userName, $password, $address) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert a new Author
    $query = "INSERT INTO users (Full_Name, email, phone_Number, User_Name, Password, UserType, Address) VALUES (?, ?, ?, ?, ?, 'Author', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $fullName, $email, $phoneNumber, $userName, $hashedPassword, $address);
    $result = $stmt->execute();

    $stmt->close();
    $dbConnection->closeConnection();

    return $result;
}

// Handle form submission for adding a new Author
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $userName = $_POST['user_name'];
    $password = $_POST['password'];
    $address = $_POST['address'];

    // Add the new Author
    if (addAuthor($fullName, $email, $phoneNumber, $userName, $password, $address)) {
        echo "Author added successfully!";
        // Redirect to avoid resubmission on page refresh
        header('Location: manage_authors.php');
        exit();
    } else {
        echo "Failed to add Author.";
    }
}

// Function to get all Authors
function getAllAuthors() {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    // Get all Authors
    $query = "SELECT * FROM users WHERE UserType = 'Author'";
    $result = $conn->query($query);

    $authors = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $authors[] = $row;
        }
    }

    $dbConnection->closeConnection();

    return $authors;
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
    $stmt->bind_param("sssssi", $fullName, $email, $phoneNumber, $userName, $hashedPassword, $address, $userId);
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

// Get all Authors
$allAuthors = getAllAuthors();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Authors</title>
</head>
<body>

<h1>Manage Authors</h1>

<!-- Add New Author Form -->
<h2>Add New Author</h2>
<form method="post">
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

    <button type="submit">Add Author</button>
</form>

<!-- List of All Authors -->
<h2>List of All Authors</h2>
<table border="1">
    <tr>
        <th>User ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Username</th>
        <th>Access Time</th>
        <th>Profile Image</th>
        <th>Address</th>
        <th>Action</th>
    </tr>
    <?php foreach ($allAuthors as $author) : ?>
        <tr>
            <td><?php echo $author['userId']; ?></td>
            <td><?php echo $author['Full_Name']; ?></td>
            <td><?php echo $author['email']; ?></td>
            <td><?php echo $author['phone_Number']; ?></td>
            <td><?php echo $author['User_Name']; ?></td>
            <td><?php echo $author['AccessTime']; ?></td>
            <td><?php echo $author['profile_Image']; ?></td>
            <td><?php echo $author['Address']; ?></td>
            <td>
                <a href="update_author.php?userId=<?php echo $author['userId']; ?>">Update</a>
                <a href="delete_author.php?userId=<?php echo $author['userId']; ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Export Authors List Buttons -->
<h2>Export Authors List</h2>
<button><a href="export_to_pdf.php?type=authors" target="_blank">Export to PDF</a></button>
<button><a href="export_to_text.php?type=authors" target="_blank">Export to Text</a></button>
<button><a href="export_to_excel.php?type=authors" target="_blank">Export to Excel</a></button>

<!-- Back to Dashboard Button -->
<br><a href="admin_dashboard.php">Go back to Dashboard</a>

</body>
</html>