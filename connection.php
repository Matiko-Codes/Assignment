<?php
// connection.php

require_once('constant.php');

class DatabaseConnection {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(HOST_NAME, DB_USER, DB_PASSWORD, DB_NAME);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}
?>