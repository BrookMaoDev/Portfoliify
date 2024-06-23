<?php

/**
 * Description: Sets up connection with MySQL database.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "constants.php";

$host = $_ENV["MARIADB_HOST"];
$dbname = DB_NAME;
$username = $_ENV["MARIADB_USERNAME"];
$password = $_ENV["MARIADB_ROOT_PASSWORD"];

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}
