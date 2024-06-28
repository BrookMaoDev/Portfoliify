<?php

/**
 * Description: If not logged in, the user will be given a link to login.php.
 * If logged in, the user will see a link to add.php and links to delete or
 * edit any resumes that are owned by the logged-in user.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "constants.php";
require_once "pdo.php";
require_once "db_queries.php";

session_start();

function createProfileRow(array $profile)
{
    $name = $profile[PROFILE_FNAME_COLNAME] . " " . $profile[PROFILE_LNAME_COLNAME];
    $headline = $profile[PROFILE_HEADLINE_COLNAME];
    echo (
        "<td><a href='view.php?" . PROFILE_ID_KEY . "=" . $profile[PROFILE_ID_COLNAME] . "'>$name</a></td>
        <td>$headline</td>");
}

function createLoggedInProfileRow(array $profile)
{
    if ($_SESSION[USER_ID_KEY] == $profile[PROFILE_USER_ID_COLNAME]) {
        // The user made this profile.
        createProfileRow($profile);
        echo (
            "<td><a href='edit.php?" . PROFILE_ID_KEY . "=" . $profile[PROFILE_ID_COLNAME] . "'>Edit</a></td>
            <td><a href='delete.php?" . PROFILE_ID_KEY . "=" . $profile[PROFILE_ID_COLNAME] . "'>Delete</a></td>");
    }
}

function createProfilesTable(array $profiles)
{
    echo (
        "<table class='table-hover'>
            <tr>
                <th>Name</th>
                <th>Headline</th>
            </tr>");

    foreach ($profiles as $profile) {
        echo "<tr>";
        createProfileRow($profile);
        echo "</tr>";
    }

    echo "</table>";
}

function createLoggedInProfilesTable(array $profiles)
{
    echo (
        "<table class='table-hover'>
            <tr>
                <th>Name</th>
                <th>Headline</th>
                <th colspan='2'>Actions</th>
            </tr>");

    foreach ($profiles as $profile) {
        echo "<tr>";
        createLoggedInProfileRow($profile);
        echo "</tr>";
    }

    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfoliify</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./static/styles.css">
</head>

<body>
    <h1 class="display-1">Portfoliify</h1>
    <div class="spacer"></div>
    <?php
    if (isset($_SESSION[SUCCESS_MSG_KEY])) {
        echo "<p style='color: green;'>" . $_SESSION[SUCCESS_MSG_KEY] . "</p>";
        unset($_SESSION[SUCCESS_MSG_KEY]);
    }

    if (isset($_SESSION[USER_ID_KEY])) { // User is signed in
        echo (
            '<h4>Your Created Portfolios</h4>
            <div class="small-spacer"></div>'
        );
        createLoggedInProfilesTable(getProfiles($db));
        echo (
            '<div class="spacer"></div>
            <div style="button-container">
                <a href="add.php" class="btn btn-outline-success">Create New Profile</a>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            </div>'
        );
    } else {
        echo (
            '<h4>Create digital resumes with ease using Portfoliify\'s HTML CVs</h4>
            <div class="spacer"></div>'
        );
        echo (
            '<div style="button-container">
                <a href="login.php" class="btn btn-outline-primary">Login</a>
                <a href="login.php" class="btn btn-outline-primary">Sign Up</a>
            </div>
            <div class="spacer"></div>
            <h4>Explore User-Created Resumes</h4>
            <div class="small-spacer"></div>'
        );
        createProfilesTable(getProfiles($db));
    }
    ?>
</body>

</html>