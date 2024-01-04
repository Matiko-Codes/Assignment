<?php
$password = "Nyamani#98";
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Generated Hashed Password: " . $hashedPassword;
?>
