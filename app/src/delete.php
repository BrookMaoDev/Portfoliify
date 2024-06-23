<?php

/**
 * Description: A page to delete an entry from the database.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "pdo.php";
require_once "constants.php";
require_once "db_queries.php";
require_once "process_superglobals.php";

const SUCCESS_MSG = "Profile deleted";

// $_POST keys
const DELETE_KEY = "delete";
const CANCEL_KEY = "cancel";

session_start();

checkLoggedIn();
checkUserHitCancel();
checkProfileGet();

$profile = requireProfile($db, (int)$_GET[PROFILE_ID_KEY]);
checkIfUserOwnsProfile($profile);

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
    <link rel="stylesheet" href="./static/styles.css">
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