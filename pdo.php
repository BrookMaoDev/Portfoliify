<?php

/**
 * Description: Sets up connection with MySQL database.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "constants.php";

$host = "localhost";
$dbname = DB_NAME;
$username = "root";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo  $e->getMessage();
}
