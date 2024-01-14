<?php

/**
 * Description: A page that shows the details for a particular entry.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "pdo.php";
require_once "constants.php";
require_once "db_queries.php";
require_once "process_superglobals.php";

checkProfileGet();

$profile = requireProfile($db, (int)$_GET[PROFILE_ID_KEY]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brook Mao's Resume Registry App</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Profile Information</h1>
    <h4>First Name: <span class="unbolded"><?= htmlentities($profile[PROFILE_FNAME_COLNAME]) ?></span></h4>
    <h4>Last Name: <span class="unbolded"><?= htmlentities($profile[PROFILE_LNAME_COLNAME]) ?></span></h4>
    <h4>Email: <span class="unbolded"><?= htmlentities($profile[PROFILE_EMAIL_COLNAME]) ?></span></h4>
    <h4>Headline: <span class="unbolded"><?= htmlentities($profile[PROFILE_HEADLINE_COLNAME]) ?></span></h4>
    <h4>Summary:</h4>
    <p><?= htmlentities($profile[PROFILE_SUMM_COLNAME]) ?></p>
    <a href="index.php">Back</a>
</body>

</html>