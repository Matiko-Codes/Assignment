<?php
session_start();

require_once('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the Super_User (you may use more secure methods)
    if ($username === 'lilmatich' && $password === 'Nyamani#98') {
        // Set a session variable to indicate that the Super_User is logged in
        $_SESSION['super_user'] = true;

        // Redirect to the dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Fetch user data from the database based on the entered username
        $conn = new DatabaseConnection();
        $connection = $conn->getConnection();

        $sql = "SELECT * FROM users WHERE IsAdministrator = ? LIMIT 1";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Validate user credentials
        if ($user && password_verify($password, $user['Password'])) {
            // Set a session variable to indicate that the user is logged in
            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['user_type'] = $user['UserType'];

            // Redirect to the dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            // Invalid credentials, redirect back to the login page with an error message
            header('Location: index.php?error=1');
            exit();
        }
    }
}
?>