<?php

/**
 * Description: Homepage for Portfoliify. Displays different content based on user login status.
 * If not logged in, the user is given links to login or sign up.
 * If logged in, the user sees links to add, delete, or edit any profiles they own.
 * Author: Brook Mao
 * Created: December 30, 2023
 */

require_once "db_connection.php";
require_once "db_queries.php";

session_start();

/**
 * Generates a table row for a profile with name and headline.
 *
 * @param array $profile Profile data
 */
function createProfileRow(array $profile)
{
    $name =
        htmlentities($profile[PROFILE_FNAME_COLNAME]) .
        " " .
        htmlentities($profile[PROFILE_LNAME_COLNAME]);
    $headline = htmlentities($profile[PROFILE_HEADLINE_COLNAME]);
    echo "<td><a href='view.php?" .
        PROFILE_ID_KEY .
        "=" .
        htmlentities($profile[PROFILE_ID_COLNAME]) .
        "'>$name</a></td>
        <td>$headline</td>";
}

/**
 * Generates a table row for a logged-in user's profile with edit and delete options.
 *
 * @param array $profile Profile data
 */
function createLoggedInProfileRow(array $profile)
{
    if ($_SESSION[USER_ID_KEY] == $profile[PROFILE_USER_ID_COLNAME]) {
        // The user owns this profile.
        createProfileRow($profile);
        echo "<td><a href='edit.php?" .
            PROFILE_ID_KEY .
            "=" .
            htmlentities($profile[PROFILE_ID_COLNAME]) .
            "'>Edit</a></td>
            <td><a href='delete.php?" .
            PROFILE_ID_KEY .
            "=" .
            htmlentities($profile[PROFILE_ID_COLNAME]) .
            "'>Delete</a></td>";
    }
}

/**
 * Generates an HTML table with profiles for all users.
 *
 * @param array $profiles List of profiles
 */
function createProfilesTable(array $profiles)
{
    echo "<table class='table-hover'>
            <tr>
                <th>Name</th>
                <th>Headline</th>
            </tr>";

    foreach ($profiles as $profile) {
        echo "<tr>";
        createProfileRow($profile);
        echo "</tr>";
    }

    echo "</table>";
}

/**
 * Generates an HTML table with profiles for the logged-in user, including edit and delete options.
 *
 * @param array $profiles List of profiles
 */
function createLoggedInProfilesTable(array $profiles)
{
    echo "<table class='table-hover'>
            <tr>
                <th>Name</th>
                <th>Headline</th>
                <th colspan='2'>Actions</th>
            </tr>";

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
    <div class="spacer"></div>
    <h1 class="display-1">Portfoliify</h1>
    <div class="small-spacer"></div>
    <?php
    if (isset($_SESSION[SUCCESS_MSG_KEY])) {
        echo "<div class='alert alert-success' role='alert'>" .
            $_SESSION[SUCCESS_MSG_KEY] .
            "</div>";
        echo '<div class="small-spacer"></div>';
        unset($_SESSION[SUCCESS_MSG_KEY]);
    }

    if (isset($_SESSION[USER_ID_KEY])) {
        // User is signed in
        echo '<h4>Your Created Profiles</h4>
            <div class="small-spacer"></div>';
        createLoggedInProfilesTable(getProfiles($db));
        echo '<div class="spacer"></div>
            <div class="button-container">
                <a href="add.php" class="btn btn-outline-success">Create New Profile</a>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            </div>';
    } else {
        echo '<h4>A profile database that allows users to create, read, update, and delete profiles easily.</h4>
            <div class="spacer"></div>';
        echo '<div class="button-container">
                <a href="login.php" class="btn btn-outline-primary">Login</a>
                <a href="signup.php" class="btn btn-outline-primary">Sign Up</a>
            </div>
            <div class="spacer"></div>
            <h4>Explore User-Created Profiles</h4>
            <div class="small-spacer"></div>';
        createProfilesTable(getProfiles($db));
    }
    ?>
    <div class="spacer"></div>
    <footer>Explore the source code on <a href="https://github.com/BrookMaoDev/Portfoliify">GitHub</a></footer>
</body>

</html>