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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./static/styles.css">
</head>

<body>
    <div class="spacer"></div>
    <h1>Are you sure you want to delete this profile for <?= htmlentities($profile[PROFILE_FNAME_COLNAME] . " " . $profile[PROFILE_LNAME_COLNAME]) ?>?</h1>
    <div class="spacer"></div>
    <form method="post">
        <input type="submit" class="btn btn-outline-warning" name="<?= DELETE_KEY ?>" value="Delete">
        <input type="submit" class="btn btn-outline-danger" name="<?= CANCEL_KEY ?>" value="Cancel">
    </form>
    <div class="spacer"></div>
</body>

</html>