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

session_start();

function getProfiles(PDO $db): array
{
    $stmt = $db->prepare("SELECT * FROM " . PROFILES_TABLE);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        return [];
    }
    return $rows;
}

function createProfileRow(array $profile)
{
    $name = $profile[PROFILE_FNAME_COLNAME] . " " . $profile[PROFILE_LNAME_COLNAME];
    $headline = $profile[PROFILE_HEADLINE_COLNAME];
    echo (
        "<td>$name</td>
        <td>$headline</td>");
}

function createLoggedInProfileRow(array $profile)
{
    createProfileRow($profile);

    if ($_SESSION[USER_ID_KEY] == $profile[PROFILE_USER_ID_COLNAME]) {
        // The user made this profile. Hence, we give them actions.
        echo (
            "<td>Edit</td>
            <td>Delete</td>");
    } else {
        echo "<td colspan='2'>None</td>";
    }
}

function createProfilesTable(array $profiles)
{
    echo (
        "<table class='custom-table'>
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
        "<table class='custom-table'>
            <tr>
                <th>Name</th>
                <th>Headline</th>
                <th colspan='2'>Action</th>
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
    <title>Brook Mao's Resume Registry App</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Brook Mao's Resume Registry App</h1>
    <?php
    if (isset($_SESSION[USER_ID_KEY])) { // User is signed in
        echo "<a href='logout.php'>Logout</a>";
        createLoggedInProfilesTable(getProfiles($db));
        echo "<a href='add.php'>Add New Entry</a>";
    } else {
        echo "<a href='login.php'>Please log in</a>";
        createProfilesTable(getProfiles($db));
    }
    ?>
</body>

</html>