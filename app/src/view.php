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
$positions = getPositions($db, (int)$_GET[PROFILE_ID_KEY]);
$educations = getEducations($db, (int)$_GET[PROFILE_ID_KEY]);

function createPositionRow($position)
{
    echo "<tr>";
    $desc = $position[POSITION_DESCRIPTION_COLNAME];
    $year = $position[POSITION_YEAR_COLNAME];
    echo "<td>$desc</td>";
    echo "<td>$year</td>";
    echo "</tr>";
}

function createPositionsTable($positions)
{
    echo (
        "<table class='table-hover'>
        <tr>
            <th>Description</th>
            <th>Year</th>
        </tr>"
    );

    foreach ($positions as $position) {
        createPositionRow($position);
    }

    echo "</table>";
}

function createEducationRow($education)
{
    echo "<tr>";
    $school_name = $education[EDUCATION_INSTITUTION_ID_COLNAME];
    $year = $education[EDUCATION_YEAR_COLNAME];
    echo "<td>$school_name</td>";
    echo "<td>$year</td>";
    echo "</tr>";
}

function createEducationsTable($educations)
{
    echo (
        "<table class='table-hover'>
        <tr>
            <th>School</th>
            <th>Year</th>
        </tr>"
    );

    foreach ($educations as $education) {
        createEducationRow($education);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./static/styles.css">
</head>

<body>
    <h1>Profile Information</h1>
    <h4>First Name: <span class="unbolded"><?= htmlentities($profile[PROFILE_FNAME_COLNAME]) ?></span></h4>
    <h4>Last Name: <span class="unbolded"><?= htmlentities($profile[PROFILE_LNAME_COLNAME]) ?></span></h4>
    <h4>Email: <span class="unbolded"><?= htmlentities($profile[PROFILE_EMAIL_COLNAME]) ?></span></h4>
    <h4>Headline: <span class="unbolded"><?= htmlentities($profile[PROFILE_HEADLINE_COLNAME]) ?></span></h4>
    <h4>Summary:</h4>
    <p><?= htmlentities($profile[PROFILE_SUMM_COLNAME]) ?></p>
    <h4>Positions:</h4>
    <?php createPositionsTable($positions); ?>
    <h4>Education:</h4>
    <?php createEducationsTable($educations); ?>
    <a href="index.php">Back</a>
</body>

</html>