<?php

/**
 * Description: Responds with a JSON array of school names for autocompletion.
 * Author: Brook Mao
 * Created: February 4, 2024
 */

require_once "db_connection.php";

// Retrieve the search term from the GET request
$term = $_GET[LOOKAHEAD_TERM_KEY];

// Prepare the SQL statement to select school names starting with the search term
$stmt = $db->prepare("SELECT " . INSTITUTION_NAME_COLNAME . " FROM " . INSTITUTION_TABLE . " WHERE " . INSTITUTION_NAME_COLNAME . " LIKE :prefix");
$stmt->execute(array(":prefix" => $term . "%"));

// Fetch the results as an associative array
$schools = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Extract the school names into a response array
$response = array();
foreach ($schools as $school) {
    $response[] = $school[INSTITUTION_NAME_COLNAME];
}

// Output the response as a JSON array with pretty print
echo json_encode($response, JSON_PRETTY_PRINT);
