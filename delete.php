<?php

/**
 * Description: A page to delete an entry from the database.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "pdo.php";
require_once "constants.php";
require_once "db_queries.php";

const SUCCESS_MSG = "Profile deleted";
const BAD_PROFILE_MSG = "Failed to retrieve profile";
const NOT_LOGGED_IN_MSG = "You are not logged in";
const NOT_OWNER_OF_PROFILE_MSG = "You are not the owner of this profile";

// $_POST keys
const DELETE_KEY = "delete";
const CANCEL_KEY = "cancel";

session_start();

// User wants to leave page
if (isset($_POST[CANCEL_KEY])) {
    header("Location: index.php");
    exit;
}

// User is not logged in
if (!isset($_SESSION[USER_ID_KEY]) || !isset($_SESSION[USER_NAME_KEY])) {
    die(NOT_LOGGED_IN_MSG);
}

// PROFILE_ID_KEY in $_GET is invalid
if (!isset($_GET[PROFILE_ID_KEY]) || !is_numeric($_GET[PROFILE_ID_KEY])) {
    die(BAD_PROFILE_MSG);
}

$profile = getProfile($db, (int)$_GET[PROFILE_ID_KEY]);

// Profile with id in $_GET does not exist in our db
if ($profile === false) {
    die(BAD_PROFILE_MSG);
}

// User is not the owner of this profile
if ($profile[PROFILE_USER_ID_COLNAME] !== $_SESSION[USER_ID_KEY]) {
    die(NOT_OWNER_OF_PROFILE_MSG);
}

// User is set on deleting this profile
if (isset($_POST[DELETE_KEY])) {
    removeResume($db, $_GET[PROFILE_ID_KEY]);
    $_SESSION[SUCCESS_MSG_KEY] = SUCCESS_MSG;
    header("Location: index.php");
    exit;
}
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
    <h1>Deleteing Profile</h1>
    <h4>First Name: <span class="unbolded"><?= htmlentities($profile[PROFILE_FNAME_COLNAME]) ?></span></h4>
    <h4>Last Name: <span class="unbolded"><?= htmlentities($profile[PROFILE_LNAME_COLNAME]) ?></span></h4>
    <form method="post">
        <input type="submit" name="<?= DELETE_KEY ?>" value="Delete">
        <input type="submit" name="<?= CANCEL_KEY ?>" value="Cancel">
    </form>
</body>

</html>