<?php
session_start();

require_once('connection.php');

function authenticateUser($username, $password) {
    $dbConnection = new DatabaseConnection();
    $conn = $dbConnection->getConnection();

    $query = "SELECT * FROM users WHERE User_Name = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        var_dump($user);

        if (password_verify($password, $user['Password'])) {
            $_SESSION['user_type'] = $user['UserType'];
            $_SESSION['user_id'] = $user['userId'];

            switch ($user['UserType']) {
                case 'SuperUser':
                    header('Location: super_user_dashboard.php');
                    break;
                case 'Administrator':
                    header('Location: admin_dashboard.php');
                    break;
                case 'Author':
                    header('Location: author_dashboard.php');
                    break;
                default:
                    echo "Unknown user type!";
                    break;
            }

            exit();
        }
    }

    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (authenticateUser($username, $password)) {
        // Authentication successful - user redirected in authenticateUser function
    } else {
        echo "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<form method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>

</body>
</html>