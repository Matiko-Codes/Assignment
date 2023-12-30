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
        // Invalid credentials, redirect back to the login page with an error message
        header('Location: index.php?error=1');
        exit();
    }
}
?>