<?php

/**
 * Description: Response consists of JSON with school names which could be used for autocompletion.
 * Author: Brook Mao
 * Created: February 4, 2024
 */

require_once "constants.php";
require_once "pdo.php";

$term = $_GET[LOOKAHEAD_TERM_KEY];

$stmt = $db->prepare("SELECT " . INSTITUTION_NAME_COLNAME .
    " FROM " . INSTITUTION_TABLE . " WHERE " . INSTITUTION_NAME_COLNAME . " LIKE :prefix");
$stmt->execute(array(":prefix" => $term . "%"));

$schools = $stmt->fetchAll(PDO::FETCH_ASSOC);
$response = array();

foreach ($schools as $school) {
    array_push($response, $school[INSTITUTION_NAME_COLNAME]);
}

echo (json_encode($response, JSON_PRETTY_PRINT));
