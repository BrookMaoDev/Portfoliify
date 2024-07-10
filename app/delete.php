<?php

/**
 * Description: A page to delete an entry from the database.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "db_connection.php";
require_once "db_queries.php";
require_once "session_helpers.php";

const SUCCESS_MSG = "Profile deleted successfully.";

// $_POST keys
const DELETE_KEY = "delete";

session_start();

// Check if the user is logged in and if the profile is accessible
checkLoggedIn();
checkUserHitCancel();
checkProfileGet();

// Fetch the profile from the database
$profile = requireProfile($db, (int) $_GET[PROFILE_ID_KEY]);

// Check if the logged-in user owns the profile
checkIfUserOwnsProfile($profile);

// If the user confirms the deletion
if (isset($_POST[DELETE_KEY])) {
    // Remove the profile from the database
    removeResume($db, (int) $_GET[PROFILE_ID_KEY]);
    // Set success message
    $_SESSION[SUCCESS_MSG_KEY] = SUCCESS_MSG;
    // Redirect to the index page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfoliify</title>

    <!-- CSS Imports -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./static/styles.css">
</head>

<body>
    <div class="spacer"></div>
    <h1>Are you sure you want to delete this profile for <?= htmlentities(
        $profile[PROFILE_FNAME_COLNAME] . " " . $profile[PROFILE_LNAME_COLNAME]
    ) ?>?</h1>
    <div class="spacer"></div>
    <form method="post">
        <input type="submit" class="btn btn-outline-warning" name="<?= DELETE_KEY ?>" value="Delete">
        <input type="submit" class="btn btn-outline-danger" name="<?= CANCEL_KEY ?>" value="Cancel">
    </form>
    <div class="spacer"></div>
</body>

</html>