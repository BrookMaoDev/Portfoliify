<?php

/**
 * Description: Sets up connection with MySQL database.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "constants.php";

// Retrieve database connection details from environment variables
$dbName = $_ENV["DB_NAME"];
$host = $_ENV["MARIADB_HOST"];
$username = $_ENV["MARIADB_USERNAME"];
$password = $_ENV["MARIADB_ROOT_PASSWORD"];

try {
    // Establish a PDO connection to MySQL database
    $db = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);

    // Set PDO attributes for error mode to throw exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Database connection failed: " . $e->getMessage();
}
